<?php

class Dailybill_register_report extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('dailybill_register_model');
        $data["login_data"] = logindata();
    }

    public function report() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$logintype=$data["login_data"]['type'];

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $branchall = $this->input->get('branch');
        $doctorall = $this->input->get('doctor');
        $credtejobuserall = $this->input->get('credtejobuser');

        $branch = $this->input->get('branch');
        $doctor = $this->input->get('doctor');
        $credtejobuser = implode(",", $this->input->get('credtejobuser'));
		
        if ($branch != "") {
            $data['getbranch'] = $this->dailybill_register_model->get_val("SELECT id,branch_name FROM branch_master WHERE STATUS='1' AND id IN($branch) ORDER BY branch_name ASC");
        } else {
            $data['getbranch'] = array();
        }

        if ($doctor != "") {
            $data['getdoctor'] = $this->dailybill_register_model->get_val("SELECT id,`full_name` FROM doctor_master WHERE STATUS='1' AND id IN($doctor) ORDER BY full_name ASC");
        } else {
            $data['getdoctor'] = array();
        }

        if ($start_date != "" || $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != "") {
			
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->dailybill_register_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}
$data["branchuser1"]=$branchuser;	

            function getjobtest($jobid) {
                if ($jobid != "") {
                    $ci = & get_instance();

                    $job_test_list = $ci->dailybill_register_model->get_val("SELECT `job_test_list_master`.*,`test_master`.`test_name` FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $jobid . "'");

                    $job_tst_lst = array();
                    foreach ($job_test_list as $st_key) {
                        $job_sub_test_list = $ci->dailybill_register_model->get_val("SELECT `sub_test_master`.test_fk,`sub_test_master`.`sub_test`,test_master.`test_name` FROM `sub_test_master` INNER JOIN `test_master` ON `sub_test_master`.`sub_test`=`test_master`.`id` WHERE `sub_test_master`.`status`='1' AND `test_master`.`status`='1' AND `sub_test_master`.`test_fk`='" . $st_key['test_fk'] . "'");
                        $st_key["sub_test"] = $job_sub_test_list;
                        $job_tst_lst[] = $st_key;
                    }
                    $package_ids = $ci->dailybill_register_model->get_job_booking_package($jobid);
                    return array("job_test_list" => $job_tst_lst, "packagename" => $package_ids);
                } else {
                    return 0;
                }
            }

            function getjobtotalpri($jobid, $start_date, $end_date, $branch, $doctor, $credtejobuser,$branchuser) {
				
                $ci = & get_instance();
                $total = $ci->dailybill_register_model->get_jobreporttotal($jobid, $start_date, $end_date, $branch, $doctor, $credtejobuser,$branchuser);
                return $total;
            }

            $this->load->library('pagination');
            $totalRows = $this->dailybill_register_model->get_jobreport('0', '0', $start_date, $end_date, $branch, $doctor, $credtejobuser,$branchuser);

            $config = array();
            $config["base_url"] = base_url() . "dailybill_register_report/report?start_date=$start_date&end_date=$end_date&branch=$branchall&doctor=$doctorall&credtejobuser=$credtejobuserall";

            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;

            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["page"] = $page;
            /* if($config["per_page"] < $page+1 ){
              $prilimt=$page-$config["per_page"];

              $data["query1"]=$this->dailybill_register_model->get_jobreportlatid($start_date,$end_date,$branch,$doctor,$credtejobuser); print_r($data["query1"]);

              die();		 $data["query1"]=$this->dailybill_register_model->get_jobreporttotal($prilimt,$start_date,$end_date,$branch,$doctor,$credtejobuser);
              } */
            $data["perpage"] = $config["per_page"];
            $data["query"] = $this->dailybill_register_model->get_jobreport($config["per_page"], $page, $start_date, $end_date, $branch, $doctor, $credtejobuser,$branchuser);
            $data["links"] = $this->pagination->create_links();
        } else {
            $data["perpage"] = "0";
            $data["page"] = "";
            $data["query"] = array();
            $data["links"] = "";
        }
        /*
          $data["branch_list"]=$this->dailybill_register_model->master_fun_get_tbl_val('branch_master','id,branch_name',array("status"=>'1'), array("branch_name","asc"));
          $data["doctor_list"]=$this->doctor_s_report_model->master_fun_get_tbl_val('doctor_master','id,full_name',array("status"=>'1'), array("full_name","asc")); */
        $data["doctor_list"] = array();

        if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) { 
		$data["admin_list"] = $this->dailybill_register_model->get_val1("SELECT `id`, `name` FROM `admin_master` WHERE `status` = '1' AND `type` != '4' AND id IN(SELECT user_fk FROM `user_branch` WHERE STATUS='1' AND TYPE != '1' AND `branch_fk` IN(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$data["login_data"]["id"].")) ORDER BY `name` ASC"); 
		
		
		}else{  $data["admin_list"] = $this->dailybill_register_model->master_fun_get_tbl_val('admin_master', 'id,name', array("status" => '1', "type !=" => '4'), array("name", "asc")); 
		}


        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('dailybill_register_report', $data);
        $this->load->view('footer');
    }

    public function report_exportcsv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $branch = $this->input->get('branch');
        $doctor = $this->input->get('doctor');
        $credtejobuserall = $this->input->get('credtejobuser');

        /* $branch=implode(",",$this->input->get('branch'));
          $doctor=implode(",",$this->input->get('doctor')); */
        $credtejobuser = implode(",", $this->input->get('credtejobuser'));


        if ($start_date != "" || $end_date != "" || $branch != "" || $doctor != "" || $credtejobuser != "") {
			
			 $data["login_data"] = logindata();
		$logintype=$data["login_data"]['type'];
		if ($logintype == 5 || $logintype == 6 || $logintype == 7 ) {
			
			 $uid = $this->dailybill_register_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS bid FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
			
			if($uid[0]["bid"] != ""){ $branchuser=$uid[0]["bid"]; }else{ $branchuser=0;  }
		}else{
			$branchuser="";
		}

            function getjobtest($jobid) {
                if ($jobid != "") {
                    $ci = & get_instance();
                    $job_tst_lst = array();

                    $job_test_list = $ci->dailybill_register_model->get_val("SELECT `test_master`.`test_name` as testname FROM `job_test_list_master` INNER JOIN `test_master` ON `job_test_list_master`.`test_fk`=`test_master`.`id` WHERE `job_test_list_master`.`job_fk`='" . $jobid . "'");
                    foreach ($job_test_list as $test) {
                        $job_tst_lst[] = ucwords($test['testname']);
                    }
                    $package_ids = $ci->dailybill_register_model->get_job_booking_package($jobid);
                    foreach ($packagename as $key3) {
                        $job_tst_lst[] = ucfirst($key3["name"]);
                    }
                    return array("job_test_list" => implode(",", $job_tst_lst));
                } else {
                    return 0;
                }
            }

            $query = $this->dailybill_register_model->get_jobreport('10000000000000', '0', $start_date, $end_date, $branch, $doctor, $credtejobuser,$branchuser);
        } else {
            $query = array();
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Dailybillregister_report.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        fputcsv($handle, array("Srl no", "Bill No", "Lab RefNo", "Patient Name", "Investigation", "Branch name", "Bill Total", "Total Paid", "Due"));
        $i = 0;
        foreach ($query as $row) {

            $jobdtest = getjobtest($row->id);
            $job_test_list = $jobdtest['job_test_list'];
            $total = round($row->price - $row->discount);
            $paid = round($total - $row->payable_amount);
            $due = round($row->payable_amount);
            $i++;
            fputcsv($handle, array($i, $row->id, $row->branch_fk, ucwords($row->full_name), $job_test_list, ucwords($row->branch_name), $total, $paid, $due));
        }
        fclose($handle);
        exit;
    }

    function getdoctore() {

        if (!is_loggedin()) {
            redirect('login');
        }
        $json_array = array();
        $term = $this->input->get('q');

        if (2 < strlen($term)) {
            $json_array = $this->dailybill_register_model->getdoctorval($term);
        }
        echo json_encode($json_array);
    }

    function getbranch() {

        if (!is_loggedin()) {
            redirect('login');
        }
		$data["login_data"] = logindata();
		$logintype=$data["login_data"]['type'];
        $json_array = array();
        $term = $this->input->get('q');

        if (2 < strlen($term)) {
			
			if ($logintype == 1 || $logintype == 2) { 
	
	$branch="";
	}else{
		
	$lablisass=$this->dailybill_register_model->get_val("SELECT GROUP_CONCAT(branch_fk) AS id FROM `user_branch` WHERE `status`='1' AND user_fk='" . $data["login_data"]["id"] . "' GROUP BY user_fk");
	
			 if($lablisass[0]['id'] != ""){ $branch=$lablisass[0]['id']; }else{ $branch="0"; }
		
	}
            $json_array = $this->dailybill_register_model->getbranchval($term,$branch);
        }
        echo json_encode($json_array);
    }

}

?>