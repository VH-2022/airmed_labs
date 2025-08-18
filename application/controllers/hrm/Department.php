<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Department extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('hrm/department_model');
        $this->load->helper('string');
        //echo current_url(); die();
        ini_set('display_errors', 'On');
        $data["login_data"] = is_hrmlogin();
    }

    function index() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('pagination');
        $search = $this->input->get('search');
        $data['search'] = $search;
        if ($search != "") {
            $total_row = $this->department_model->search_num($search);
            $config = array();
            $get = $_GET;
            $config["base_url"] = base_url() . "hrm/department/index?" . http_build_query($get);
            $config["total_rows"] = $total_row;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->department_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        } else {
            $search = "";
            $totalRows = $this->department_model->search_num($search);
            $config = array();
            $config["base_url"] = base_url() . "hrm/department/index/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->department_model->list_search($search, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
        $data['designation_list'] = $this->department_model->get_all('hrm_designation', array("status" => 1));
        $this->load->view('hrm/header');
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/department_list', $data);
        $this->load->view('hrm/footer');
    }

    function add() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/department_add', $data);
        $this->load->view('hrm/footer', $data);
    }

    function add_salary_structure($id) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $data['query'] = $this->department_model->get_one("hrm_department", array("id" => $id, "status" => 1));

        $data['designation_list'] = $this->department_model->get_all('hrm_designation', array("status" => 1, "department_fk" => $data['query']->id));

        if ($_POST) {
            $count_designation = $this->input->post('count_designation');
            $department_fk = $this->input->post('department_id');
            $designation_fk = $this->input->post('designation_id');
            $designation_name = $this->department_model->get_one("hrm_designation", array("id" => $designation_fk));

            for ($j = 1; $j <= $count_designation; $j++) {
                $designation = $this->input->post('designation_' . $j);
                if ($designation != "") {
                    $data1 = array(
                        "department_fk" => $department_fk,
                        "designation_fk" => $designation_fk,
                        "salary_name" => $designation,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s")
                    );
                    $value = $this->department_model->insert("hrm_salary_structure", $data1);

                    $data['added_data'] = $this->department_model->get_all("hrm_salary_structure", array("designation_fk" => $designation_fk));

                    $data['designation_name'] = $this->department_model->get_all("hrm_designation", array("id" => $designation_fk));

                    $this->session->set_flashdata("success", "Salary structure has added successfully.");
                }
            }
        }

        $data['salary_list1'] = $this->department_model->get_all("hrm_salary_structure", array("department_fk" => $id, "status" => "1"));
        $final_array = array();
        foreach ($data['designation_list'] as $key) {
            $salary = $this->department_model->get_all("hrm_salary_structure", array("department_fk" => $key->department_fk, "designation_fk" => $key->id, "status" => "1"));
            $key->designation = $salary;
            $final_array[] = $key;
        }
        $data["final_array"] = $final_array;
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/add_salary_structure', $data);
        $this->load->view('hrm/footer', $data);
    }

    public function edit_salary_popup() {
        $this->load->model('hrm/department_model');
        $designation_id = $this->input->post('designation_id');
        $data = $this->department_model->get_all('hrm_salary_structure', array("designation_fk" => $designation_id, "status" => "1"));

        $desigantion_count = count($data);
        $edit_html = "";
        $cnt = 0;

        foreach ($data as $key) {

//            if ($key->cutofftype == 2) {
//                $cutselect = "selected";
//            }
            
//            echo "<pre>"; print_r($key);
            $cutselect1 = "";
            $cutselect2 = "";
            if ($key->cutofftype == 1) {
                $cutselect1 = "selected";
            }
            if ($key->cutofftype == 2) {
                $cutselect2 = "selected";
            }

            $cnt++;
            if ($key->saletype == '5') {
                $renovtr = '<a href="javascript:void(0)" onclick="row_remove(' . $cnt . ');"><i style="color:red;" class="fa fa-minus-square"></i></a>';
            } else {
                $renovtr = '';
            }

            $edit_html .='<tr id="tr_' . $cnt . '"><td>' . $renovtr . '</td><td><input type="hidden" name="designation1_id_' . $cnt . '" value="' . $key->id . '"><input type="text" name="designation1[]" value="' . $key->salary_name . '" id="designation1_' . $cnt . '" class="form-control"><span id="designation_error_' . $cnt . '" style="color:red;"></span></td><td><select name="cuttype[]" class="form-control"><option ' . $cutselect1 . ' value="1">Fix</option><option ' . $cutselect2 . ' value="2">Percentages</option></select></td><td><input type="text" placeholder="value" name="pricevalue[]" id="pricevalue_' . $cnt . '" value="' . $key->value . '" class="form-control pricevalue"></td></tr>';
        }

        if ($desigantion_count == 0) {

            $edit_html .='<tr id="tr_1">
    <td></td>
                    <td>
                        <input type="text" readonly name="designation1[]" value="Basic" id="designation1_1" class="form-control">
                    </td>
					<td>
					<select name="cuttype[]" class="form-control"><option value="1">Fix</option><option value="2">Percentages</option></select></td>
					<td><input type="text" name="pricevalue[]" placeholder="value" id="pricevalue_1" class="form-control pricevalue"></td>
                </tr>
				<tr id="tr_2">
    <td></td>
                    <td>
                        
                         <input type="text" readonly name="designation1[]" value="Employee PF" id="designation1_2" class="form-control">
                    </td>
					<td>
					<select name="cuttype[]" class="form-control"><option value="1">Fix</option><option value="2">Percentages</option></select></td>
					<td><input type="text" name="pricevalue[]" placeholder="value" id="pricevalue_2" class="form-control pricevalue"></td>
                </tr>
				<tr id="tr_3">
    <td></td>
                    <td>
                        
                         <input type="text" readonly name="designation1[]" value="Employer PF" id="designation1_3" class="form-control">
                    </td>
					<td>
					<select name="cuttype[]" class="form-control"><option value="1">Fix</option><option value="2">Percentages</option></select></td>
					<td><input type="text" name="pricevalue[]" placeholder="value" id="pricevalue_3" class="form-control pricevalue"></td>
                </tr>
				<tr id="tr_4">
    <td></td>
                    <td>
                        
                         <input type="text" readonly name="designation1[]" value="Esic" id="designation1_4" class="form-control">
                    </td>
					<td>
					<select name="cuttype[]" class="form-control"><option value="1">Fix</option><option value="2">Percentages</option></select></td>
					<td><input type="text" name="pricevalue[]" placeholder="value" id="pricevalue_3" class="form-control pricevalue"></td>
                </tr>';
        }

        echo '<thead>
                <tr>
                    <th>Add/Remove</th>
                    <th>Salary Name</th>
					<th></th>
					<th></th>
                </tr>
            </thead>
            <tbody id="table_body1">
			' . $edit_html . '
			
                <tr id="tr_0">
    <td>
                        <a class="srch_view_a" href="javascript:void(0)" onclick="add_field1();"><i class="fa fa-plus-square"></i></a>
                    </td>
                    <td>
                        <input type="hidden" name="count_designation1" id="count_designation1" value=' . $desigantion_count . '>
                        <input type="text" name="designation1[]" id="designation1_0" class="form-control">
                        <span id="designation_error_0" style="color:red;"></span>
                    </td>
					<td>
					<select name="cuttype[]" class="form-control"><option value="1">Fix</option><option value="2">Percentages</option></select></td>
					<td><input type="text" name="pricevalue[]" placeholder="value" id="pricevalue_0" class="form-control pricevalue"></td>
                </tr>
            </tbody>';
    }

    function edit_salary() {
        $data["login_data"] = is_hrmlogin();
        if ($_POST) {
//            echo "<pre>"; print_r($_POST); exit;
            $department_id = $this->input->post('department_id');
            $designation_fk = $this->input->post('designation_id_edit');
            $designation1 = $this->input->post('designation1');

            $cuttype = $this->input->post('cuttype');
            $pricevalue = $this->input->post('pricevalue');

            $value = $this->department_model->update("hrm_salary_structure", array("department_fk" => $department_id, "designation_fk" => $designation_fk), array("status" => "0"));
            for ($j = 0; $j < count($designation1); $j++) {
                if (trim($designation1[$j]) != "") {
                    if ($j == 0) {
                        $type = 1;
                    } else if ($j == 1) {
                        $type = 2;
                    } else if ($j == 2) {
                        $type = 3;
                    } else if ($j == 3) {
                        $type = 4;
                    } else {
                        $type = 5;
                    }

                    $value = $this->department_model->insert("hrm_salary_structure", array("department_fk" => $department_id, "designation_fk" => $designation_fk, "salary_name" => $designation1[$j], "cutofftype" => $cuttype[$j], "value" => $pricevalue[$j], "saletype" => $type, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
                }
            }
            $data['added_data'] = $this->department_model->get_all("hrm_salary_structure", array("designation_fk" => $designation_fk));
            $data['designation_name'] = $this->department_model->get_all("hrm_designation", array("id" => $designation_fk));

            $this->session->set_flashdata("success", "Salary structure has updated successfully.");
            redirect("hrm/department/add_salary_structure/$department_id", "refresh");
        }
    }

    function add_all() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $department_name = trim($this->input->post('department_name'));
        $insert = $this->department_model->insert("hrm_department", array("name" => $department_name, "created_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
        $count_designation = $this->input->post('count_designation');
        for ($j = 1; $j <= $count_designation; $j++) {
            $designation = trim($this->input->post('designation_' . $j));
            if ($designation != "") {
                $data1 = array(
                    "department_fk" => $insert,
                    "name" => $designation,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $value = $this->department_model->insert("hrm_designation", $data1);
            }
        }
        $this->session->set_flashdata("success", "Department successfully added.");
        redirect("hrm/department", "refresh");
    }

    function delete($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['query'] = $this->department_model->update("hrm_department", array("id" => $cid), array("status" => '0'));
        $data['query'] = $this->department_model->update("hrm_designation", array("department_fk" => $cid), array("status" => '0'));
        $this->session->set_flashdata("success", "Department successfully deleted.");
        redirect("hrm/department", "refresh");
    }

    function edit($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['cid'] = $cid;
        $data['query'] = $this->department_model->get_one("hrm_department", array("id" => $cid, "status" => 1));
        $data['designation_list'] = $this->department_model->get_all('hrm_designation', array("status" => 1, "department_fk" => $cid));
        $this->load->view('hrm/header', $data);
        $this->load->view('hrm/nav', $data);
        $this->load->view('hrm/department_edit', $data);
        $this->load->view('hrm/footer', $data);
    }

    function edit_all($cid) {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $department_name = trim($this->input->post('department_name'));
        $update = $this->department_model->update("hrm_department", array("id" => $cid), array("name" => $department_name, "updated_by" => $data["login_data"]["id"], "updated_date" => date("Y-m-d H:i:s")));
        $count_designation = $this->input->post('count_designation');
        $edit_count_designation = $this->input->post('edit_count_designation');
        for ($j = 1; $j <= $edit_count_designation; $j++) {
            $edit_designation_id = $this->input->post('edit_designation_id_' . $j);
            $edit_designation = trim($this->input->post('edit_designation_' . $j));
            if ($edit_designation != "") {
                $data1 = array(
                    "department_fk" => $cid,
                    "name" => $edit_designation,
                    "updated_by" => $data["login_data"]["id"],
                    "updated_date" => date("Y-m-d H:i:s")
                );
                $value = $this->department_model->update("hrm_designation", array("id" => $edit_designation_id), $data1);
            }
        }
        for ($j = 1; $j <= $count_designation; $j++) {
            $designation = trim($this->input->post('designation_' . $j));
            if ($designation != "") {
                $data1 = array(
                    "department_fk" => $cid,
                    "name" => $designation,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s")
                );
                $value = $this->department_model->insert("hrm_designation", $data1);
            }
        }
        $this->session->set_flashdata("success", "Department successfully updated.");
        redirect("hrm/department", "refresh");
    }

    function remove_designation() {
        if (!is_hrmlogin()) {
            redirect('login');
        }
        $data["login_data"] = is_hrmlogin();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $designation_id = $this->input->post('designation_id');
        $update = $this->department_model->update("hrm_designation", array("id" => $designation_id), array("status" => 0));
        if ($update) {
            return 1;
        }
    }

}

?>
