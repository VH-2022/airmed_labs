<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Doctor_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('user_model');
        $this->load->model('doctor_model');
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

    function doctor_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $city_name = $this->input->get('city');
        $sales_person = $this->input->get('sales_person');
        if ($name != "" || $email != "" || $mobile != "" || $city_name != "" || $sales_person != "") {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "city" => $city_name, "sales_person" => $sales_person);
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            /* Vishal Code Start */
            $data['city_id'] = $city_name;
            $data['selected_person'] = $sales_person;

            /* Vishal Code Start */

            $totalRows = $this->doctor_model->doctorcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "doctor_master/doctor_list?name=$name&email=$email&mobile=$mobile&city=$city_name&sales_person=$sales_person";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->doctor_model->doctorlist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $srchdata = array();
            $totalRows = $this->doctor_model->doctorcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "doctor_master/doctor_list/";
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
            $data["query"] = $this->doctor_model->doctorlist_list($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }

        $data['state'] = $this->doctor_model->master_fun_get_tbl_val("state", array("status" => 1), array("id", "asc"));
        $data['city'] = $this->doctor_model->master_fun_get_tbl_val("test_cities", array("status" => 1), array("id", "asc"));


        $data['sales_person'] = $this->doctor_model->master_fun_get_tbl_val("sales_user_master", array("status" => 1), array("id", "asc"));

        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('doctor_list', $data);
        $this->load->view('footer');
    }

    function doctor_add() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['state_list'] = $this->doctor_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');
        //$this->form_validation->set_rules('city', 'City', 'trim|required');
        //$this->form_validation->set_rules('state', 'State', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $pass = $this->input->post('password');
            $mobile = $this->input->post('mobile');
            $mobile1 = $this->input->post('mobile1');
            $mobile2 = $this->input->post('mobile2');
            $add = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $notify = $this->input->post('notify');
            $ref_id = $this->input->post('ref_id');
            if ($notify != 1) {
                $notify = 0;
            }
            $data['query'] = $this->doctor_model->master_fun_insert("doctor_master", array("city" => $city, "state" => $state, "full_name" => $name, "email" => $email, "mobile" => $mobile, "mobile1" => $mobile1, "mobile2" => $mobile2, "address" => $add, "password" => $pass, "notify" => $notify, "ref_id" => $ref_id));
            $this->session->set_flashdata("success", array("Doctor successfully added."));
            redirect("doctor_master/doctor_list", "refresh");
        } else {

            $data['sales_person'] = $this->doctor_model->master_fun_get_tbl_val("sales_user_master", array("status" => 1), array("id", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('doctor_add', $data);
            $this->load->view('footer');
        }
    }

    function doctor_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), array("status" => "0"));
        $this->session->set_flashdata("success", array("Doctor successfully deleted."));
        redirect("doctor_master/doctor_list", "refresh");
    }

    function doctor_active() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), array("status" => "1"));
        $this->session->set_flashdata("success", array("Doctor successfully Activited."));
        redirect("doctor_master/doctor_list", "refresh");
    }

    function doctor_deactive() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), array("status" => "2"));
        $this->session->set_flashdata("success", array("Doctor successfully Deactivited."));
        redirect("doctor_master/doctor_list", "refresh");
    }

    function doctor_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["cid"] = $this->uri->segment('3');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        //$this->form_validation->set_rules('password', 'Password', 'trim|required');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');
        //$this->form_validation->set_rules('city', 'City', 'trim|required');
        //$this->form_validation->set_rules('state', 'State', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $mobile = $this->input->post('mobile');
            $mobile1 = $this->input->post('mobile1');
            $mobile2 = $this->input->post('mobile2');
            $pass = $this->input->post('password');
            $add = $this->input->post('address');
            $city = $this->input->post('city');
            $state = $this->input->post('state');
            $notify = $this->input->post('notify');
            $ref_id = $this->input->post('ref_id');
            if ($notify != 1) {
                $notify = 0;
            }
            $data['query'] = $this->doctor_model->master_fun_update("doctor_master", array("id", $data["cid"]), array("city" => $city, "state" => $state, "full_name" => $name, "email" => $email, "mobile" => $mobile, "mobile1" => $mobile1, "mobile2" => $mobile2, "address" => $add, "password" => $pass, "notify" => $notify, "ref_id" => $ref_id));
            $this->session->set_flashdata("success", array("Doctor successfully updated."));
            redirect("doctor_master/doctor_list", "refresh");
        } else {
            $data['query'] = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("id" => $data["cid"]), array("id", "desc"));
            $data['state_list'] = $this->doctor_model->master_fun_get_tbl_val("state", array("status" => 1), array("state_name", "asc"));
            $data['city_list'] = $this->doctor_model->master_fun_get_tbl_val("city", array("state_fk" => $data['query'][0]['state']), array("city_name", "asc"));
            $data['sales_person'] = $this->doctor_model->master_fun_get_tbl_val("sales_user_master", array("status" => 1), array("id", "asc"));

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('doctor_edit', $data);
            $this->load->view('footer');
        }
    }

    function city_state_list() {
        $cid = $this->input->post('cid');
        $data = $this->doctor_model->master_fun_get_tbl_val("city", array("state_fk" => $cid), array("city_name", "asc"));
        echo '<option value="">--Select--</option>';
        foreach ($data as $all) {
            echo "<option value='" . $all['id'] . "'>" . $all['city_name'] . "</option>";
        }
    }

    public function import_list() {
        $cnt = 0;
        $this->load->library('csvimport');
        $state = $this->input->post('state');
        //$city = $this->input->post('city');

        $test_city = $this->input->post('city');
        $city_arr = $this->doctor_model->master_fun_get_tbl_val("test_cities", array("id" => $test_city), array("city_fk", "asc"));
        $city = $city_arr[0]['city_fk'];


        $file = $_FILES['id_browes']['name'];
        if ($file != "") {
            $file = $_FILES['id_browes']['name'];
            $filename = $_FILES['id_browes']['name'];
            $filename = md5(time()) . $filename;
            $output['status'] = FALSE;
            set_time_limit(0);
            $config['upload_path'] = "./upload/";
            $output['image_medium2'] = $config['upload_path'];
            $config['allowed_types'] = '*';
            $config['file_name'] = $filename;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('id_browes')) {
                $error = array($this->upload->display_errors());
                $this->session->set_flashdata('success', array($error));
                if (!empty($this->session->userdata("test_master_r"))) {
                    redirect($this->session->userdata("test_master_r"), "refresh");
                } else {
                    redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
                }
            } else {
                $file_data = $this->upload->data();
                $file_path = './upload/' . $file_data['file_name'];

                if ($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $cnt = 0;
                    $cnt2 = 0;
                    $cnt3 = 0;
                    $cnt4 = 0;

                    foreach ($csv_array as $row) {
                        $cnt3++;
                        $old_test = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("status" => 1, "mobile" => $row['Mobile']), array("id", "desc"));
                        if (empty($old_test)) {

                            if ($row['Mobile'] != "") {
                                $cnt++;
                                $data['query'] = $this->doctor_model->master_fun_insert("doctor_master", array("full_name" => $row['Name'], "email" => $row['Email'], "mobile" => $row['Mobile'], "address" => $row['Address'], "state" => $state, "city" => $city));
                            } else {
                                $cnt2++;
                            }
                        } else {
                            if ($row['Mobile'] != "") {
                                $data['query'] = $this->doctor_model->master_fun_update("doctor_master", array("mobile", $row['Mobile']), array("full_name" => $row['Name'], "email" => $row['Email'], "address" => $row['Address'], "state" => $state, "city" => $city));
                            }
                            $cnt4++;
                        }
                    }
                }
            }
        }
//        if ($cnt == '0') {
//           echo $ses = "Error occured";
//            $this->session->set_flashdata('success', array($ses));
//            if (!empty($this->session->userdata("test_master_r"))) {
//            //    redirect($this->session->userdata("test_master_r"), "refresh");
//            } else {
//            //    redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
//            }
//        } else {
//         echo   $ses = array($cnt . "Doctor Added Successfully");
//         echo "added".$cnt."<br> total".$cnt3."<br> non mobile".$cnt2."<br> allready".$cnt4;
//            $this->session->set_flashdata('success', $ses);
//            if (!empty($this->session->userdata("test_master_r"))) {
//               // redirect($this->session->userdata("test_master_r"), "refresh");
//            } else {
//              //  redirect("b2b/logistic_test_master/test_list/" . $lid, "refresh");
//            }
//        }

        $this->session->set_flashdata('success', array("Doctor Added Successfully"));
        redirect("doctor_master/doctor_list", "refresh");
    }

    public function setdpermission() {

        $pcid = $this->uri->segment('3');
        $query = $this->doctor_model->fetchdatarow("id,app_permission", "doctor_master", array("id" => $pcid, "status" => '1'));
        if ($query != "") {
            if ($query->app_permission != '1') {
                $data = array('app_permission' => '1');
                $this->session->set_flashdata('success', array('Successfully Activated permission'));
            } else {
                $data = array('app_permission' => '0');
                $this->session->set_flashdata('success', array('Successfully Deactivated Permissions'));
            }


            $this->doctor_model->master_fun_update('doctor_master', array("id", $pcid), $data);

            redirect("doctor_master/doctor_list");
        } else {
            show_404();
        }
    }
	
	public function export_csv_doctor(){
		$name = trim($_GET['name']);
		$email = trim($_GET['email']);
		$mobile = trim($_GET['mobile']); 
		$city = trim($_GET['city']);
		$sales_person = trim($_GET['sales_person']);
		$qry = null;
		
		if($name != ""){
			$qry .= "WHERE dm.full_name LIKE '%".$name."%'";
		}
		
		if($email != ""){
			if($qry==null){
				$qry .= "WHERE dm.email=".$email;
			}else{
				$qry .= " AND dm.email=".$email;
			}
		}
		
		if($mobile !=""){
			if($qry==null){
				$qry .= "WHERE dm.mobile=".$mobile;
			}else{
				$qry .= " AND dm.mobile=".$mobile;
			}
		}
		
		if($city!=""){
			if($qry==null){
				$qry .= "WHERE dm.city=".$city;
			}else{
				$qry .= " AND dm.city=".$city;
			}
		}
		
		if($sales_person!=""){
			if($qry==null){
				$qry .= "WHERE dm.ref_id=".$sales_person;
			}else{
				$qry .= " AND dm.ref_id=".$sales_person;
			}
		}
		
		if($qry==null){
			$qry .= "WHERE dm.status IN(1,2)";
		}else{
			$qry .= " AND dm.status IN(1,2)";
		}
		
		$sql = "SELECT dm.full_name, dm.email, dm.mobile, dm.mobile1, dm.mobile2, c.city_name, s.state_name, dm.address, CONCAT(sum.first_name, ' ', sum.last_name) AS sales_person, dm.status FROM doctor_master AS dm
				LEFT JOIN city AS c ON c.id = dm.city AND c.status = 1
				LEFT JOIN state AS s ON s.id = dm.state AND s.status = 1
				LEFt JOIN sales_user_master sum ON sum.id = dm.ref_id AND sum.status = 1
				$qry";
				
		$final_array = $this->doctor_model->get_val($sql);	
		
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"doctormaster_list.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("No.", "Doctor Name", "Email", "Mobile", "Alternate Mobile", "Reception Mobile", "City Name", "State Name", "Address", "Sales Person", "Status"));

        $count = 0;
        foreach ($final_array as $val) {
			$count = $count + 1;
			fputcsv($handle, array($count, $val["full_name"], $val["email"], $val['mobile'], $val["mobile1"], $val["mobile2"], $val["city_name"], $val["state_name"], $val["address"] , $val["sales_person"] , ($val["status"]==1)?"Active":"De-Active"));
		}
		
		fclose($handle);
        exit;
	}
}

?>