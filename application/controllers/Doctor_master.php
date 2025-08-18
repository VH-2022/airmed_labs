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
        $data['notifyReport']=$notifyReport = $this->input->get('notify');
        $data['status']=$status = $this->input->get('status');

        if ($name != "" || $email != "" || $mobile != "" || $city_name != "" || $sales_person != "" || $notifyReport!="" || $status!="") {
            $srchdata = array("name" => $name, "email" => $email, "mobile" => $mobile, "city" => $city_name, "sales_person" => $sales_person,'notifyReport'=>$notifyReport,'status'=>$status);
            $data['name'] = $name;
            $data['mobile'] = $mobile;
            $data['email'] = $email;
            /* Vishal Code Start */
            $data['city_id'] = $city_name;
            $data['selected_person'] = $sales_person;

            /* Vishal Code Start */

            $totalRows = $this->doctor_model->doctorcount_list($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "doctor_master/doctor_list?name=$name&email=$email&mobile=$mobile&city=$city_name&sales_person=$sales_person&notifyReport=$notifyReport&status=$status";
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
		
		$data['prolist'] = $this->doctor_model->master_fun_get_tbl_val("pro_master", array("pro_status" => 1,'pro_active_status'=>1), array("id", "asc"));
        
        $data['specialitylist'] = $this->doctor_model->master_fun_get_tbl_val("doctor_speciality_master", array("status" => 1), array("id", "asc"));

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
        if (!array_search('doctor_add', $data["login_data"]["permissions"]))
        {
            return $this->load->view('permission_not');
        }
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
        $status = trim($_GET['status']);
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
			$qry .= "WHERE dm.id NOT IN(0)";
		}else{
			$qry .= " AND dm.id NOT IN(0)";
		}

        if($status!=""){
			if($qry==null){
				$qry .= "WHERE dm.status=".$status;
			}else{
				$qry .= " AND dm.status=".$status;
			}
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
	
    function doctor_alias()
    {

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $cid = $this->uri->segment('3');
        $code = $_POST["code"];
        $speciality = $_POST["speciality"];
        $pro = $_POST["pro"];
        $pro_id = $_POST["pro_id"];
        $ratio = $_POST["ratio"];
        $area = $_POST["area"];

        $docMasterResult = $this->doctor_model->get_val("SELECT * FROM `doctor_master` WHERE `id` = '" . $cid . "'");
        $doctor_pro_id = "";
        if (isset($docMasterResult[0]['doctor_pro_id'])) {
            $doctor_pro_id =    $docMasterResult[0]['doctor_pro_id'];
        }

        $o_data = array("doctor_code" => $code, "doctor_speciality" => $speciality, "doctor_pro" => $pro, "doctor_pro_id" => $pro_id, "doctor_ratio" => $ratio, "doctor_area" => $area);
        $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), $o_data);
        $o_data["id"] = $cid;

         $this->doctor_model->master_fun_insert("doctor_pro_change_hisotry", array("doctor_id" => $cid, "new_pro_id" =>$pro_id, "old_pro_id" => $doctor_pro_id, "created_by"=>$data["login_data"]["id"]));

        if ($doctor_pro_id != $pro_id && $pro_id != "") {
            $firstDayThisMonth = date('Y-m-01 00:00:00');
        
            $job_master= $this->doctor_model->get_val("SELECT * FROM `job_master` WHERE `date` > '" . $firstDayThisMonth . "' and `doctor`= '" . $cid . "'");
         
            foreach($job_master as $master){
                $data['query'] = $this->user_model->master_fun_update("job_master",array('id',$master['id']), array('pro_id'=>$pro_id));
            }
          
            
        }
        echo 'Code Saved Successfully';
        exit;
        //$this->session->set_flashdata("success", array("Doctor Code Saved."));
        //redirect("doctor_master/doctor_list", "refresh");
    }

    function doctorproadd()
    {

        if (!is_loggedin()) {
            redirect('login');
        }
      

        $docMasterResult = $this->doctor_model->get_val("SELECT `id`,`doctor_pro_id` FROM `doctor_master` WHERE `doctor_pro_id` IS NOT NULL");
        // echo "<pre>". count($docMasterResult); 
        // print_r($docMasterResult[0]['id']);die;
        foreach($docMasterResult as $docs){
            $firstDayThisMonth = date('Y-m-01 00:00:00');
            $data['query'] = $this->user_model->master_fun_update1("job_master",array('doctor'=>$docs['id'],'date >'=>$firstDayThisMonth), array('pro_id'=>$docs['doctor_pro_id']));
        }
       
        // die;
        // $doctor_pro_id = "";
        // if (isset($docMasterResult[0]['doctor_pro_id'])) {
        //     $doctor_pro_id =    $docMasterResult[0]['doctor_pro_id'];
        // }

        // $o_data = array("doctor_code" => $code, "doctor_speciality" => $speciality, "doctor_pro" => $pro, "doctor_pro_id" => $pro_id, "doctor_ratio" => $ratio, "doctor_area" => $area);
        // $data['query'] = $this->user_model->master_fun_update("doctor_master", array("id", $cid), $o_data);
        // $o_data["id"] = $cid;

        // if ($doctor_pro_id != $pro_id && $pro_id != "") {
        //     $firstDayThisMonth = date('Y-m-01 00:00:00');
        
        //     $job_master= $this->doctor_model->get_val("SELECT * FROM `job_master` WHERE `date` > '" . $firstDayThisMonth . "' and `doctor`= '" . $cid . "'");
         
        //     foreach($job_master as $master){
        //         $data['query'] = $this->user_model->master_fun_update("job_master",array('id',$master['id']), array('pro_id'=>$pro_id));
        //     }
          
            
        // }
        echo 'Code Saved Successfully';
        exit;
        //$this->session->set_flashdata("success", array("Doctor Code Saved."));
        //redirect("doctor_master/doctor_list", "refresh");
    }

    public function update_pro_name() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $cnt = 0;
        $this->load->library('csvimport');
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
                    redirect("doctor_master/doctor_list", "refresh");
                }
            } else {

                $file_data = $this->upload->data();
                $file_path = './upload/' . $file_data['file_name'];
                if($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    $no = 2;
                    foreach ($csv_array as $row) {
                        $old_test = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("status" => 1, "mobile" => $row['doctor_mobile']), array("id", "desc"));

                        $get_pro_name = $this->doctor_model->master_fun_get_tbl_val("pro_master", array("pro_status" => 1, "pro_name" => $row['pro_name']), array("id", "desc"));

                        if (empty($row['pro_name'])) {
                            $this->session->set_flashdata('error', "Row: " . $no . " pro name is required");
                            redirect("doctor_master/doctor_list", "refresh");
                        }

                        if (empty($row['doctor_mobile'])) {
                            $this->session->set_flashdata('error', "Row: " . $no . " doctor mobile is required");
                            redirect("doctor_master/doctor_list", "refresh");
                        }
                        
                        if (empty($old_test)) {
                            $this->session->set_flashdata('error', "Row: " . $no . " doctor mobile is invalid");
                            redirect("doctor_master/doctor_list", "refresh");
                        }

                        if (empty($get_pro_name)) {
                            $this->session->set_flashdata('error', "Row: " . $no . " pro name is invalid");
                            redirect("doctor_master/doctor_list", "refresh");
                        }
                        $no++;
                        
                    }
                    
                    foreach ($csv_array as $row) {
                        $old_test = $this->doctor_model->master_fun_get_tbl_val("doctor_master", array("status" => 1, "mobile" => $row['doctor_mobile']), array("id", "desc"));

                        $get_pro_name = $this->doctor_model->master_fun_get_tbl_val("pro_master", array("pro_status" => 1, "pro_name" => $row['pro_name']), array("id", "desc"));
                        
                        if(!empty($old_test) && !empty($get_pro_name)){
                            $data['query'] = $this->doctor_model->master_fun_update("doctor_master", array("mobile", $row['doctor_mobile']), array("doctor_pro" => $get_pro_name[0]['pro_name'], "doctor_pro_id" => $get_pro_name[0]['id']));
                        }
                    }
                }
            }
        }

        $this->session->set_flashdata('success', array("Pro name update Successfully"));
        redirect("doctor_master/doctor_list", "refresh");
    }

    function test_csv()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $city_name = $this->input->get('city');
        $sales_person = $this->input->get('sales_person');
        $doc_code = $this->input->get('doc_code');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Doctor_List.csv";

        $query = "SELECT DISTINCT d.id as id,d.full_name as doctor_name,d.doctor_code as doctor_code,d.mobile as doctor_mobile,p.pro_name as pro_name from doctor_master d left join city c on c.id=d.city left join state s on s.id=d.state left join sales_user_master as sales_person  on sales_person.id=d.ref_id left join pro_master p ON p.id = d.doctor_pro where d.status IN (1,2) AND d.full_name!='' AND d.mobile != ''  AND d.doctor_code != ''";
        
        if ($name != "") {
            $query .= " AND d.full_name LIKE '%$name%'";
        }
        if ($email != "") {
            $query .= " AND d.email LIKE '%$email%'";
        }
        if ($mobile != "") {
            $query .= " AND d.mobile LIKE '%$mobile%'";
        }
        if ($city_name != "") {
            $query .= " AND d.city LIKE '%$city_name%'";
        }
        if ($sales_person != "") {
            $query .= " AND d.ref_id LIKE '%$sales_person%'";
        }
        if ($doc_code != "") {
            $query .= " AND d.doctor_code LIKE '%$doc_code%'";
        }
        $query .= " AND d.doctor_code !='' ORDER BY d.id DESC";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    function test_csv_speciality()
    {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile');
        $email = $this->input->get('email');
        $city_name = $this->input->get('city');
        $sales_person = $this->input->get('sales_person');
        $doc_code = $this->input->get('doc_code');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Doctor_List.csv";

        $query = "SELECT DISTINCT d.id as id,d.full_name as doctor_name,d.doctor_code as doctor_code,d.mobile as doctor_mobile,dsm.doctor_speciality_title as doctor_speciality from doctor_master d left join city c on c.id=d.city left join state s on s.id=d.state left join sales_user_master as sales_person  on sales_person.id=d.ref_id left join doctor_speciality_master dsm ON dsm.id = d.doctor_speciality where d.status IN (1,2) AND d.full_name!='' AND d.mobile != ''  AND d.doctor_code != ''";

        if ($name != "") {
            $query .= " AND d.full_name LIKE '%$name%'";
        }
        if ($email != "") {
            $query .= " AND d.email LIKE '%$email%'";
        }
        if ($mobile != "") {
            $query .= " AND d.mobile LIKE '%$mobile%'";
        }
        if ($city_name != "") {
            $query .= " AND d.city LIKE '%$city_name%'";
        }
        if ($sales_person != "") {
            $query .= " AND d.ref_id LIKE '%$sales_person%'";
        }
        if ($doc_code != "") {
            $query .= " AND d.doctor_code LIKE '%$doc_code%'";
        }
        $query .= " AND d.doctor_code !='' ORDER BY d.id DESC";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
    }

    public function import_speciality() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $this->load->library('csvimport');
        $this->load->library('upload');

        if (!empty($_FILES['import_speciality_file']['name'])) {

            $filename = md5(time()) . '_' . $_FILES['import_speciality_file']['name'];

            $config['upload_path']   = './upload/';
            $config['allowed_types'] = 'csv';
            $config['file_name']     = $filename;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('import_speciality_file')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect("doctor_master/doctor_list", "refresh");
            }

            $file_data = $this->upload->data();

            $file_path = './upload/' . $file_data['file_name'];

            if ($this->csvimport->get_array($file_path)) {

                $csv_array = $this->csvimport->get_array($file_path);

                $row_num = 2;

                foreach ($csv_array as $row) {
                    if (empty($row['doctor_speciality'])) {
                        $this->session->set_flashdata('error', "Row $row_num: Doctor Speciality is required.");
                        redirect("doctor_master/doctor_list", "refresh");
                    }

                    if (empty($row['doctor_mobile'])) {
                        $this->session->set_flashdata('error', "Row $row_num: Doctor Mobile is required.");
                        redirect("doctor_master/doctor_list", "refresh");
                    }

                    $doctor = $this->doctor_model->master_fun_get_tbl_val("doctor_master", ["status" => 1, "mobile" => $row['doctor_mobile']], ["id", "desc"]);
                    if (empty($doctor)) {
                        $this->session->set_flashdata('error', "Row $row_num: Invalid Doctor Mobile.");
                        redirect("doctor_master/doctor_list", "refresh");
                    }

                    $speciality = $this->doctor_model->master_fun_get_tbl_val("doctor_speciality_master", ["status" => 1, "doctor_speciality_title" => $row['doctor_speciality']], ["id", "desc"]);
                    if (empty($speciality)) {
                        $this->session->set_flashdata('error', "Row $row_num: Invalid Doctor Speciality.");
                        redirect("doctor_master/doctor_list", "refresh");
                    }

                    $row_num++;
                }

                // Second pass: Update
                foreach ($csv_array as $row) {
                    $speciality = $this->doctor_model->master_fun_get_tbl_val("doctor_speciality_master", ["status" => 1, "doctor_speciality_title" => $row['doctor_speciality']], ["id", "desc"]);

                    if(!empty($speciality)){
                        $data['query'] = $this->doctor_model->master_fun_update("doctor_master", array("mobile", $row['doctor_mobile']), array("doctor_speciality" => $speciality[0]['id']));
                    }
                }

                $this->session->set_flashdata('success', "Doctor Speciality updated successfully.");

                redirect("doctor_master/doctor_list", "refresh");
            } else {
                $this->session->set_flashdata('error', "CSV parsing error. Please upload a valid file.");
            }

        } else {
            $this->session->set_flashdata('error', "No file selected.");
        }
        redirect("doctor_master/doctor_list", "refresh");
    }
}

?>