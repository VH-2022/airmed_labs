<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('pathologist/pathologist_model');
		
       $logincheck=is_pathologist();
        if (!$logincheck){
            redirect('pathologist');
			die();
        }
       // $this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
        
        $data["login_data"] = is_pathologist();
		$uid=$data["login_data"]["id"];
		$time_stamp =date("Y-m-d H:i:s");
		
		$startdate=$this->input->get("startdate");
		$enddate=$this->input->get("enddate");
		if($startdate != ""){ $data["startdate"]=$startdate;  }else{ $data["startdate"]=date("d-m-Y"); }
		
		if($enddate != ""){ $data["enddate"]=$enddate;  }else{ $data["enddate"]=date("d-m-Y"); }
		
		$serachdata=array("startdate" =>date("Y-m-d",strtotime($data["startdate"])),"enddate"=>date("Y-m-d",strtotime($data["enddate"])));
		
		$branch_list = $this->pathologist_model->master_fun_get_tbl_val("user_branch", array("user_fk" => $uid, "status" => "1"), array("id", "desc"));
		
        $user_branch = array();
        if (!empty($branch_list)) {
            foreach ($branch_list as $key) {
                $user_branch[] = $key["branch_fk"];
            }
        }
		$data["branch_list"]=$this->pathologist_model->get_val("SELECT id,`branch_name` FROM branch_master WHERE STATUS='1' AND id IN(".implode(",",$user_branch).")");
		
		$job_status = $this->pathologist_model->get_job_status($user_branch,$serachdata);
		
        $final_data = array();
        $status1 = 0;
        foreach ($job_status as $key) {
            $status1 = $status1 + $key["count"];
        }
        $final_data[0]["total_jobs"] = "" . $status1;
        $status1 = 0;
		
		$status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 1) {
                $final_data[0]["waiing_for_approve"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["waiing_for_approve"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 6) {
                $final_data[0]["approve"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["approve"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 7) {
                $final_data[0]["sample_collected"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["sample_collected"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 8) {
                $final_data[0]["processing"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["processing"] = "0";
        }
        $status1 = 0;
        foreach ($job_status as $key) {
            if ($key["status"] == 2) {
                $final_data[0]["completed"] = $key["count"];
                $status1 = 1;
            }
        } if ($status1 == 0) {
            $final_data[0]["completed"] = "0";
        }
		$data["final_data"]=$final_data;
		 /* echo "<pre>"; print_r($final_data); die(); */ 
		$this->load->view('pathologist/pathologist_header',$data);
        $this->load->view('pathologist/pathologist_dashbords');
       
    
	}
	function patientlist() {
        
        $data["login_data"] = is_pathologist();
		$uid=$data["login_data"]["id"];
		$time_stamp =date("Y-m-d H:i:s");
		
		$branch_list = $this->pathologist_model->master_fun_get_tbl_val("user_branch", array("user_fk" => $uid, "status" => "1"), array("id", "desc"));
		
		
		
        $user_branch = array();
        if (!empty($branch_list)) {
            foreach ($branch_list as $key) {
                $user_branch[] = $key["branch_fk"];
            }
        }
		if($user_branch == null){ $user_branch=array('0'); }
		
		$data["branch_list"]=$this->pathologist_model->get_val("SELECT id,`branch_name` FROM branch_master WHERE STATUS='1' AND id IN(".implode(",",$user_branch).")");
		
		$from = $this->input->get("enddate");
		if($from != ""){ $from = date("Y-m-d",strtotime($from));  }else{ $from = date("Y-m-d"); }
        
        $to = $this->input->get("startdate");
       if($to != ""){ $to = date("Y-m-d",strtotime($to));  }else{ $to = date("Y-m-d"); }
       $type = $this->input->get("type");
	   if($type == ""){ $type="8"; }else if($type=="12"){ $type=""; }
	  
	   $branch = $this->input->get("branch");
	   $data["branch"]=$branch;
	   $data["from"]=$from;
	   $data["to"]=$to;
	   $data["type"]=$type;
	  
            $dataquery = $this->pathologist_model->patient_data($from, $to, $type, $user_branch,$branch);
			
            $new_array = array();
			$j=0;
			$this->load->library("util");
        $util = new Util;
          foreach ($dataquery as $key1) {
				$jobid=$key1["id"];
				
				
				
				$jobtestlist= $this->pathologist_model->get_val("SELECT id,`test_name` FROM  test_master WHERE STATUS='1' AND (id IN(SELECT test_fk FROM job_test_list_master WHERE job_fk='$jobid') or id IN(SELECT test_fk FROM `package_test` WHERE STATUS='1' AND `package_fk` IN(SELECT package_fk FROM book_package_master WHERE STATUS='1' AND `job_fk`='$jobid')) )");
				
				$dataquery[$j]["jobtestlist"]=$jobtestlist;
	  
	  $apotest=$this->pathologist_model->get_val("SELECT GROUP_CONCAT(test_fk) as aptest FROM approve_job_test WHERE STATUS='1' AND job_fk='$jobid'");
	  
	  $dataquery[$j]["apotest"]=$apotest;
	  
				
				$totaltest=0;
				$totaltest=$this->pathologist_model->get_val("SELECT COUNT(id) as totaltest FROM job_test_list_master WHERE STATUS='1' AND job_fk='$jobid'");
				
				$totalpacktest=$this->pathologist_model->get_val("SELECT COUNT(id) AS totaltest FROM `package_test` WHERE STATUS='1' AND `package_fk` IN(SELECT package_fk FROM book_package_master WHERE STATUS='1' AND `job_fk`='$jobid')");
				
				$dataquery[$j]["totaltest"]=($totaltest[0]["totaltest"]+$totalpacktest[0]["totaltest"]);
				
				$apototaltest=$this->pathologist_model->get_val("SELECT COUNT(test_fk) as aptest FROM approve_job_test WHERE STATUS='1' AND job_fk='$jobid'");
				
				$dataquery[$j]["approvetest"]=$apototaltest[0]["aptest"];
				
				$age= $util->get_age($key1["dob"]);
				$getage="";
				if ($age[0] != 0) {
					$getage=$age[0]." Y";
                }
                if ($age[0] == 0 && $age[1] != 0) {

					$getage=$age[1]." M";
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                   
                   $getage=$age[2]." D";
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                    
					$getage="0 D";
                }
				$dataquery[$j]["age"]=$getage;
				
$j++;			 
            }
			$data["new_array"]=$dataquery;
			
        
		  /* echo "<pre>"; print_r($data); die();   */
		$this->load->view('pathologist/pathologist_header',$data);
        $this->load->view('pathologist/patient_list_views');
       
    
	}
	function patientlistsearch() {
        
        $data["login_data"] = is_pathologist();
		$uid=$data["login_data"]["id"];
		$time_stamp =date("Y-m-d H:i:s");
		
		$branch_list = $this->pathologist_model->master_fun_get_tbl_val("user_branch", array("user_fk" => $uid, "status" => "1"), array("id", "desc"));
		
		
		
        $user_branch = array();
        if (!empty($branch_list)) {
            foreach ($branch_list as $key) {
                $user_branch[] = $key["branch_fk"];
            }
        }
		if($user_branch == null){ $user_branch=array('0'); }

		
		$from = $this->input->get("enddate");
		if($from != ""){ $from = date("Y-m-d",strtotime($from));  }else{ $from = date("Y-m-d"); }
        
        $to = $this->input->get("startdate");
       if($to != ""){ $to = date("Y-m-d",strtotime($to));  }else{ $to = date("Y-m-d"); }
       $type = $this->input->get("type");
	   
	  if($type == ""){ $type="8"; }else if($type=="12"){ $type=""; }
	  
	   $branch = $this->input->get("branch");
	   $data["branch"]=$branch;
	   $data["from"]=$from;
	   $data["to"]=$to;
	   $data["type"]=$type;
	  
            $dataquery = $this->pathologist_model->patient_data($from, $to, $type, $user_branch,$branch);
			
            $new_array = array();
			$j=0;
			$this->load->library("util");
        $util = new Util;
          foreach ($dataquery as $key1) {
				$jobid=$key1["id"];
				
				
				
				$jobtestlist= $this->pathologist_model->get_val("SELECT id,`test_name` FROM  test_master WHERE STATUS='1' AND (id IN(SELECT test_fk FROM job_test_list_master WHERE job_fk='$jobid') or id IN(SELECT test_fk FROM `package_test` WHERE STATUS='1' AND `package_fk` IN(SELECT package_fk FROM book_package_master WHERE STATUS='1' AND `job_fk`='$jobid')) )");
				
				$dataquery[$j]["jobtestlist"]=$jobtestlist;
	  
	  $apotest=$this->pathologist_model->get_val("SELECT GROUP_CONCAT(test_fk) as aptest FROM approve_job_test WHERE STATUS='1' AND job_fk='$jobid'");
	  
	  $dataquery[$j]["apotest"]=$apotest;
	  
				
				$totaltest=0;
				$totaltest=$this->pathologist_model->get_val("SELECT COUNT(id) as totaltest FROM job_test_list_master WHERE STATUS='1' AND job_fk='$jobid'");
				
				$totalpacktest=$this->pathologist_model->get_val("SELECT COUNT(id) AS totaltest FROM `package_test` WHERE STATUS='1' AND `package_fk` IN(SELECT package_fk FROM book_package_master WHERE STATUS='1' AND `job_fk`='$jobid')");
				
				$dataquery[$j]["totaltest"]=($totaltest[0]["totaltest"]+$totalpacktest[0]["totaltest"]);
				
				$apototaltest=$this->pathologist_model->get_val("SELECT COUNT(test_fk) as aptest FROM approve_job_test WHERE STATUS='1' AND job_fk='$jobid'");
				
				$dataquery[$j]["approvetest"]=$apototaltest[0]["aptest"];
				
				$age= $util->get_age($key1["dob"]);
				$getage="";
				if ($age[0] != 0) {
					$getage=$age[0]." Y";
                }
                if ($age[0] == 0 && $age[1] != 0) {

					$getage=$age[1]." M";
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
                   
                   $getage=$age[2]." D";
                }
                if ($age[0] == 0 && $age[1] == 0 && $age[2] == 0) {
                    
					$getage="0 D";
                }
				$dataquery[$j]["age"]=$getage;
				
$j++;			 
            }
			$data["new_array"]=$dataquery;
			
        
		  /* echo "<pre>"; print_r($data); die();   */
		
       $html=$this->load->view('pathologist/patient_searchlist_views',$data,true);
       echo $html;
    
	}
function patient_job_test() {
       $data["login_data"] = is_pathologist();
		$uid=$data["login_data"]["id"];
		$time_stamp =date("Y-m-d H:i:s");
		
		$job_id = $this->input->get("job_fk");
		
		$branch_list = $this->pathologist_model->master_fun_get_tbl_val("user_branch", array("user_fk" => $uid, "status" => "1"), array("id", "desc"));
        $user_branch = array();
        if (!empty($branch_list)) {
            foreach ($branch_list as $key) {
                $user_branch[] = $key["branch_fk"];
            }
        }
		if($user_branch == null){ $user_branch=array('0'); }
		
      $data = $this->pathologist_model->get_val("SELECT j.id,j.order_id,j.`price`,j.`status`,c.`full_name`,c.dob,c.`mobile`,c.`gender` FROM job_master j INNER JOIN customer_master c ON c.id=j.`cust_fk` WHERE j.`status` !='0' AND j.`branch_fk` in (" . implode(",", $user_branch) . ") and j.id='$job_id'");
	  
	  $data["jobdetils"]=$data;
	  if($data != null){
	  $data["jobtestlist"]= $this->pathologist_model->get_val("SELECT id,`test_name` FROM  test_master WHERE STATUS='1' AND (id IN(SELECT test_fk FROM job_test_list_master WHERE job_fk='$job_id') or id IN(SELECT test_fk FROM `package_test` WHERE STATUS='1' AND `package_fk` IN(SELECT package_fk FROM book_package_master WHERE STATUS='1' AND `job_fk`='$job_id')) )");
	  
	  $data["apotest"]=$this->pathologist_model->get_val("SELECT GROUP_CONCAT(test_fk) as aptest FROM approve_job_test WHERE STATUS='1' AND job_fk='$job_id'");
	  
	  $data["procestest"]=$this->pathologist_model->get_val("SELECT GROUP_CONCAT(test_id) as processtest FROM user_test_result WHERE status='1' AND job_id='$job_id'");
	  
	 
	  $data["jobpacklist"]= array();
	  
	  }else{
		  $data["jobtestlist"]= array();
		   $data["apotest"]=array();
	  }
	  
			
		  /*  echo "<pre>"; print_r($data["jobtestdetils"]); die(); */
		   
		$this->load->view('pathologist/pathologist_header',$data);
        $this->load->view('pathologist/jobtestlist_views');
    }
function all_test_approve_details_mail() {
	$data["login_data"] = is_pathologist();
		$uid=$data["login_data"]["id"];
       $this->load->model('add_result_model');
	   $data['cid'] = $this->input->get("jid");
        $data['tid1'] = $this->input->get("tid");
        $data['query'] = $this->add_result_model->job_details($data['cid']);
        $data['processing_center'] = $this->add_result_model->master_fun_get_tbl_val("processing_center", array('status' => 1, 'lab_fk' => $data['query'][0]["branch_fk"]), array("id", "asc"));
        if (empty($data['processing_center'])) {
            $processing_center = '1';
        } else {
            $processing_center = $data['processing_center'][0]["branch_fk"];
        }
		$this->load->library("util");
        $util = new Util;
        $data["age"] = $util->get_age($data['query'][0]["dob"]);
        //echo "<pre>";print_R($data['query']); die();
        $data['user_booking_info'] = $this->add_result_model->master_fun_get_tbl_val("booking_info", array('status' => 1, 'id' => $data['query'][0]["booking_info"]), array("id", "asc"));
        $data['user_data'] = $this->add_result_model->master_fun_get_tbl_val("customer_master", array('status' => 1, 'id' => $data['query'][0]["custid"]), array("id", "asc"));
        if (empty($data['user_data'][0]["gender"]) && empty($data['user_data'][0]["age"])) {
            $data['user_data'][0]["gender"] = 'male';
            $data['user_data'][0]["age"] = 24;
            $data['user_data'][0]["age_type"] = 'Y';
        }

        if ($data['user_booking_info'][0]["family_member_fk"] != 0) {
            $data['user_family_info'] = $this->add_result_model->master_fun_get_tbl_val("customer_family_master", array('status' => 1, 'id' => $data['user_booking_info'][0]["family_member_fk"]), array("id", "asc"));
            $data['user_data'][0]["gender"] = $data['user_family_info'][0]["gender"];
            $data['user_data'][0]["age"] = $data['user_family_info'][0]["age"];
            $data['user_data'][0]["age_type"] = $data['user_family_info'][0]["age_type"];
            $data['user_data'][0]["full_name"] = $data['user_family_info'][0]["name"];
            $data['user_data'][0]["email"] = $data['user_family_info'][0]["email"];
            $data['user_data'][0]["phone"] = $data['user_family_info'][0]["phone"];
            $data['user_data'][0]["dob"] = $data['user_family_info'][0]["dob"];
        }
        if (empty($data['user_data'][0]["dob"])) {
            $data['user_data'][0]["dob"] = '1992-09-30';
        }
        /* Check bitrth date start */
        $this->load->library("util");
        $util = new Util;
        $age = $util->get_age($data['user_data'][0]["dob"]);
        $ageinDays = 0;
        if ($age[0] != 0) {
            $ageinDays += ($age[0] * 365);
            $data['user_data'][0]["age"] = $age[0];
            $data['user_data'][0]["age_type"] = 'Y';
        }
        if ($age[0] == 0 && $age[1] != 0) {
            $ageinDays += ($age[1] * 30);
            $data['user_data'][0]["age"] = $age[1];
            $data['user_data'][0]["age_type"] = 'M';
        }
        if ($age[0] == 0 && $age[1] == 0 && $age[2] != 0) {
            $ageinDays += ($age[2]);
            $data['user_data'][0]["age"] = $age[2];
            $data['user_data'][0]["age_type"] = 'D';
        }
        /* Check birth date end */

        $tid111 = explode(",", $data['tid1']);
        $tid = array();
        foreach ($tid111 as $rid_key) {
            if (!in_array($rid_key, $tid)) {
                $tid[] = $rid_key;
            }
        }
        $cnt = 0;
        $new_data_array = array();
        foreach ($tid as $tst_id) {
            $get_test_parameter = $this->add_result_model->get_val("SELECT `test_parameter`.*,`test_master`.`test_name`,`test_master`.PRINTING_NAME,`test_master`.`report_type` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' and `test_parameter`.`center`='" . $processing_center . "' order by `test_parameter`.order asc");
//echo "SELECT `test_parameter`.*,`test_master`.`test_name` FROM `test_parameter` INNER JOIN `test_master` ON `test_parameter`.`test_fk`=`test_master`.`id` WHERE `test_parameter`.`status`='1' AND `test_master`.`status`='1' AND `test_parameter`.`test_fk`='" . $tst_id . "' order by `test_parameter`.order asc"; die();
            $pid = array();
            foreach ($get_test_parameter as $tp_key) {
                if (!empty($tp_key["parameter_fk"])) {
                    $pid[] = $tp_key["parameter_fk"];
                }
            }
            if (!empty($pid)) {
                $para = $this->add_result_model->get_val("SELECT * FROM `test_parameter_master` WHERE `status`='1' AND id IN (" . implode(",", $pid) . ") ORDER BY FIELD(id," . implode(",", $pid) . ")");
                if (!empty($para)) {
                    $cnt_1 = 0;
                    foreach ($para as $para_key) {
                        $formula = $this->add_result_model->get_val("SELECT * from use_formula where test_fk='" . $tst_id . "' and job_fk='" . $data['cid'] . "' and status='1'");
                        $get_test_parameter[$cnt_1]['use_formula'] = $formula[0]["use_formula"];
                        $get_test_parameter[$cnt_1]['on_new_page'] = $formula[0]["on_new_page"];
                        /* Report type start */
                        $culure_design = $this->add_result_model->get_val("SELECT id,title,html FROM `parameter_culture` WHERE center='" . $processing_center . "' AND test_fk='" . $tst_id . "' AND STATUS='1'");
                        $get_test_parameter[$cnt_1]['culture_design'] = $culure_design;
                        /* End */
                        $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                        $get_test_parameter[$cnt_1]['graph'] = $graph_pic;
                        $get_test_parameter[$cnt_1]['graph_id'] = $formula[0]["id"];
                        $get_test_parameter1 = $get_test_parameter[$cnt_1];
//print_R($get_test_parameter1); die();
//echo "SELECT * from user_test_result where test_id='".$tst_id."' and parameter_id='" . $para_key["id"] . "' and job_id='".$data['cid']."' and  status='1'";
                        $para_user_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and parameter_id='" . $para_key["id"] . "' and job_id='" . $data['cid'] . "' and  status='1'");
                        $para[$cnt_1]["user_val"] = $para_user_val;
                        $para_culture_val = $this->add_result_model->get_val("SELECT * from user_test_result where test_id='" . $tst_id . "' and job_id='" . $data['cid'] . "' and result is not NULL and status='1'");
                        $para[$cnt_1]["user_culture_val"] = $para_culture_val;
                        $para_ref_rng = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE `status`='1' AND parameter_fk='" . $para_key["id"] . "' order by gender asc");
                        $final_qry = "SELECT *,
  CASE
    WHEN (type_period = 'Y') 
    THEN (no_period * 365) 
    ELSE (
      CASE
        WHEN (type_period = 'M') 
        THEN (no_period * 30) 
        ELSE no_period 
      END
    ) 
  END AS col1 FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        /* if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 6 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age"] > 5 && $data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          $data["common"] = 0;
                          } else if ($data['user_data'][0]["age"] > 0 && $data['user_data'][0]["age"] < 8 && $data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_both_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='B' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          //$get_male_age = $this->add_result_model->get_val("SELECT * FROM `parameter_referance_range` WHERE STATUS='1' AND `parameter_fk`='117' AND gender='M' AND `no_period` > ".$data['user_data'][0]["age"]." AND `type_period`='Y' ORDER BY id ASC");
                          //print_r($get_male_age);
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if ($data['user_data'][0]["age"] == '') {
                            $data['user_data'][0]["age"] = 0;
                        }
                        /* if ($data['user_data'][0]["age_type"] == 'D') {
                          $final_qry .= " AND gender='N'  AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='D'";
                          } else if ($data['user_data'][0]["age_type"] == 'M') {
                          $final_qry .= " AND gender='C' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='M'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'MALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='M' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='F' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          }
                          else if ($para_ref_rng[0]["gender"] == 'B' && $data['user_data'][0]["age_type"] == 'Y') {
                          $final_qry .= " AND gender='B' AND `no_period` > " . $data['user_data'][0]["age"] . " AND `type_period`='Y'";
                          $data["common"] = 0;
                          } */
                        if (strtoupper($data['user_data'][0]["gender"]) == 'MALE') {
                            $final_qry .= " AND gender='M' AND (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays ";
                            $data["common"] = 0;
                        } else if (strtoupper($data['user_data'][0]["gender"]) == 'FEMALE') {
                            $final_qry .= " AND gender='F' AND  (CASE WHEN (type_period= 'Y') THEN (no_period*365) ELSE (CASE WHEN (type_period= 'M') THEN (no_period*30) ELSE no_period END) END )>=$ageinDays";
                            $data["common"] = 0;
                        }
                        $final_qry = $final_qry . " ORDER BY (col1*1) ASC limit 0,1";
                        $final_qry1 = "SELECT * FROM `test_result_status` WHERE STATUS='1' AND `parameter_fk`='" . $para_key["id"] . "'";
                        $data["common"] = 1;
                        $data["para_ref_rng"] = $this->add_result_model->get_val($final_qry);
                        $data["para_ref_rng"][0]["common"] = "1";
                        $data["para_ref_rng"][0]["tst_id"] = $tst_id;
                        $para[$cnt_1]['para_ref_rng'] = $data["para_ref_rng"];

                        $data["para_ref_status"] = $this->add_result_model->get_val($final_qry1);
                        $para[$cnt_1]['para_ref_status'] = $data["para_ref_status"];
                        $para[$cnt_1]["test_parameter_id"] = $get_test_parameter1["id"];
                        $para[$cnt_1]["new_order"] = $get_test_parameter1["order"];
                        $cnt_1++;
                    }
                    $get_test_parameter1[0]['parameter'] = $para;
                    $new_data_array[] = $get_test_parameter1;
                } else {
                    $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                    $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                    $get_test_parameter1[0]['graph'] = $graph_pic;
                    $new_data_array[] = $get_test_parameter1[0];
                }
            } else {
                $get_test_parameter1 = $this->add_result_model->get_val("SELECT id as test_fk,test_name FROM `test_master` WHERE id='" . $tst_id . "'");
                $graph_pic = $this->add_result_model->get_val("SELECT * FROM user_formula_pic WHERE `status`='1' AND job_fk='" . $data['cid'] . "' AND test_fk='" . $tst_id . "'");
                $get_test_parameter1[0]['graph'] = $graph_pic;
                $new_data_array[] = $get_test_parameter1[0];
            }

            $cnt++;
        }
        $data["new_data_array"] = $new_data_array;
        $data['result_list'] = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('status' => 1, 'job_id' => $data['cid']), array("id", "asc"));
	   
			$this->load->view('pathologist/pathologist_header',$data);
            $this->load->view('pathologist/test_approved_views');
        
    }
function add_value_exists2() {
	
	$data["login_data"] = is_pathologist();
		$uid=$data["login_data"]["id"];
		$time_stamp =date("Y-m-d H:i:s");
		$this->load->model('add_result_model');
        $files = $_FILES;
        $this->load->library('upload');
        
		$data["login_data"] = logindata();
        $count = $this->input->post('count');
        $tid = $this->input->post('tid');
        $para_job_id = $this->input->post('para_job_id');
       
        $test_id_array = array();
        for ($i = 0; $i < $count; $i++) {
            $para_id = $this->input->post('parameter_id_' . $i);
            $test_id = $this->input->post('test_id_' . $i);
            //$test_id = $tid[$i];
            $value = $this->input->post('parameter_value_' . $i);
            $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id), array("status" => "0"));
            if ($value != '') {
                $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
                if (empty($check_val)) {
                    $data = array(
                        "job_id" => $para_job_id,
                        "parameter_id" => $para_id,
                        "value" => $value,
                        "test_id" => $test_id,
                        "created_by" => $data["login_data"]["id"],
                        "created_date" => date("Y-m-d H:i:s"),
                    );
                    $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
                } else {
                    $data = array("value" => $value, "status" => "1");
                    $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
                }
            }
            $check_is_approved = $this->add_result_model->master_fun_get_tbl_val("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $test_id, "status" => "1"), array("id", "asc"));
            if (empty($check_is_approved)) {
                $insert = $this->add_result_model->master_fun_insert("approve_job_test", array('job_fk' => $para_job_id, "test_fk" => $test_id, "approve_by" => $data["login_data"]["id"], "created_date" => date("Y-m-d H:i:s")));
            }
        }
        if ($value != '') {
            $check_val = $this->add_result_model->master_fun_get_tbl_val("user_test_result", array('job_id' => $para_job_id, "parameter_id" => $para_id, "test_id" => $test_id, "status" => 1), array("id", "asc"));
            if (empty($check_val)) {
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => $para_id,
                    "value" => $value,
                    "test_id" => $test_id,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            } else {
                $data = array("value" => $value);
                $val_add = $this->add_result_model->master_fun_update("user_test_result", array("id", $check_val[0]['id']), $data);
            }
        }

        $test_fk = $this->input->post('test_fk');
        foreach ($test_fk as $key) {
            $formula = $this->input->post('use_formula_' . $key);
            $on_new_page = $this->input->post('on_new_page_' . $key);
            if ($formula == '') {
                $formula = 0;
            }
            if ($on_new_page == '') {
                $on_new_page = 0;
            }
            /* Culture result add start */
            $culture_result = $this->input->post('culture_design_' . $key);
            $culture_result_fk = $this->input->post("culture_design_fk_" . $key);
            if (!empty($culture_result)) {
                $this->add_result_model->master_fun_update1("user_test_result", array('job_id' => $para_job_id, "parameter_id" => 0, "test_id" => $key), array("status" => "0"));
                $data = array(
                    "job_id" => $para_job_id,
                    "parameter_id" => "",
                    "value" => "",
                    "value2" => "",
                    "test_id" => $key,
                    "result" => $culture_result,
                    "result_fk" => $culture_result_fk,
                    "created_by" => $data["login_data"]["id"],
                    "created_date" => date("Y-m-d H:i:s"),
                );
                $val_add = $this->add_result_model->master_fun_insert("user_test_result", $data);
            }
            /* END */
            /* Nishit Graph Upload start */
            
            $file_loop = count($_FILES['graph_' . $key]['name']);
            $file_upload = array();
            if (!empty($_FILES['graph_' . $key]['name'])) {
                for ($i = 0; $i < $file_loop; $i++) {
                    $_FILES['userfile']['name'] = $files['graph_' . $key]['name'][$i];
                    $_FILES['userfile']['type'] = $files['graph_' . $key]['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['graph_' . $key]['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['graph_' . $key]['error'][$i];
                    $_FILES['userfile']['size'] = $files['graph_' . $key]['size'][$i];
                    $config['upload_path'] = './upload/report/graph/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['file_name'] = time() . $files['graph_' . $key]['name'][$i];
                    $config['file_name'] = str_replace(' ', '_', $config['file_name']);
                    $config['overwrite'] = FALSE;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata("error", array($error));
                        //redirect('job-master/job-details/' . $cid);
                    } else {
                        $file_upload[] = array("test_fk" => $key, "pic" => $config['file_name']);
                    }
                }
            }
            //print_R($file_upload); die();
            /* Nishit Graph Upload end */
            if (!in_array($key, $test_id_array)) {
                if ($graph_name == '') {
                    $graph_name = $this->input->post('current_graph_' . $key);
                    ;
                }
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "use_formula" => $formula,
                    "on_new_page" => $on_new_page,
                    // "graph" => $graph_name,
                    "status" => 1
                );
                $test_id_array[] = $key;
                //  $val_add = $this->add_result_model->master_fun_insert("use_formula", $data12);
            }
            foreach ($file_upload as $file_key) {
                $data12 = array(
                    "job_fk" => $para_job_id,
                    "test_fk" => $key,
                    "pic" => $file_key["pic"],
                    "status" => 1,
                    "createddate" => date("Y-m-d H:i:s")
                );
                $val_add = $this->add_result_model->master_fun_insert("user_formula_pic", $data12);
            }
        }

        $this->add_result_model->master_fun_insert("job_log", array("job_fk" => $para_job_id, "created_by" =>"", "updated_by" =>$uid, "deleted_by" => "", "job_status" => '', "message_fk" => "32", "date_time" => date("Y-m-d H:i:s")));
        
		echo "1";
    }	
	
}
