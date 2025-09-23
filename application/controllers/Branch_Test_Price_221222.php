<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Branch_Test_Price extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('Branch_Model');
        $this->load->model('registration_admin_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('pushserver');
        $this->load->library('email');
        $this->load->helper('string');
//        ini_set('display_errors', 'On');
//echo current_url(); die();

        $data["login_data"] = logindata();
    }

    function edit_test_price($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $this->form_validation->set_rules('test_price_id', 'test_price_id', 'trim|required');
        $this->form_validation->set_rules('price', 'price', 'trim|required');
        if ($this->form_validation->run() != FALSE) {
            $tid = $this->input->post("test_price_id");
            $price = $this->input->post("price");
            $test_name = $this->input->post("test_name");
            $testtype = $this->input->post("testtype");


            if (!empty($price) && is_numeric($price) && $price >= 0) {
                $update = $this->Branch_Model->master_tbl_update("test_branch_price", $tid, array("price" => $price));

                $this->session->set_flashdata("success", array("Price successfully updated."));
                redirect("Branch_Test_Price/edit_test_price/$id?test_name=$test_name&ttype=$testtype");
            }
        } else {

            $data['user'] = $this->user_model->getUser($data["login_data"]["id"]);
            $data['branch_name'] = $this->Branch_Model->get_val("select branch_name from branch_master where id=$id");
            $name = $this->input->get("test_name");
            $ttype = $this->input->get("ttype");
            //echo "<pre>"; print_r($data['branch_name']); exit;
            $data['cid'] = $id;
            if ($ttype == 2) {

                $q = "SELECT tp.id,tp.price,tp.test_fk,tp.branch_fk,tp.status,tm.title AS test_name, tp.r_code FROM package_master tm INNER JOIN test_branch_price tp ON tp.test_fk = tm.id   WHERE tp.branch_fk='$id' AND tm.status ='1' AND tp.`type`='2'";

                if ($name != "") {
                    $name = trim($_GET['test_name']);
                    $q .= "  AND tm.title LIKE '%$name%'";
                    $data['test_name'] = $name;
                }
            } else {

                $q = "SELECT tp.id,tp.price,tp.test_fk,tp.branch_fk,tp.status,tm.test_name, tp.r_code
                FROM test_master tm LEFT JOIN test_branch_price tp
                on tp.test_fk = tm.id 
                WHERE tp.branch_fk= $id AND tm.status = 1 AND tp.`type`='1' ";

                if ($name != "") {
                    $name = trim($_GET['test_name']);
                    $q .= "  AND tm.test_name LIKE '%$name%'";
                    $data['test_name'] = $name;
                }
            }

            $data['query'] = $this->Branch_Model->get_val($q);

            $totalRows = count($data['query']);
            $config = array();
            $config["base_url"] = base_url() . "Branch_Test_Price/edit_test_price/$id";
            $config["total_rows"] = $totalRows;

            $config['page_query_string'] = TRUE;
            $config["per_page"] = 1000;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;

            $two = $config['per_page'];
            $q .= " LIMIT $page,$two";
            $data["query"] = $this->Branch_Model->get_val($q);

            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;

            $city = $this->Branch_Model->get_val("select id from test_cities where id IN(select city from branch_master where id = $id)");

            $city_id = $city[0]->id;

//            $test_query = "select id,test_name 
//                    from test_master 
//                    WHERE test_master.id NOT IN (select test_fk from test_branch_price where type = '1' AND status != '0') 
//                    AND status = '1'
//                    ";

            $test_query = "select DISTINCT test_master.id,test_master.test_name 
                    from test_master 
                    INNER JOIN test_master_city_price on test_master.id = test_master_city_price.test_fk
                    WHERE test_master.id NOT IN (select test_fk from test_branch_price where type = '1' AND branch_fk='$id' AND status != '0') 
                    AND test_master.status = '1' AND test_master_city_price.city_fk = $city_id
                    ";

            $data["test_data_1"] = $this->Branch_Model->get_val($test_query);
//            echo "<pre>"; print_r($data["test_data_1"]); exit;
//            $package_query = "select id,title from package_master 
//                    WHERE id NOT IN (select test_fk from test_branch_price where type = '2' AND status != '0') 
//                    AND status = '1'
//                    ";

            $package_query = "select DISTINCT package_master.id,package_master.title 
                    from package_master 
                    INNER JOIN package_master_city_price on package_master.id = package_master_city_price.package_fk
                    WHERE package_master.id NOT IN (select test_fk from test_branch_price where type = '2' AND branch_fk='$id' AND status != '0') 
                    AND package_master.status = '1' AND package_master_city_price.city_fk = '$city_id'";

            $data["package_data_1"] = $this->Branch_Model->get_val($package_query);
            //echo "<pre>"; print_r($data["package_data_1"]); exit;

            $this->load->view('header');
            $this->load->view('nav', $data);
            $this->load->view('edit_test_price', $data);
            $this->load->view('footer');
        }
    }

    function add_test($id) {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        //echo "<pre>"; print_r($_POST); exit;    

        $test_fk = $this->input->post("select_test");

        $start_d = explode("_", $test_fk);
        $test_package_fk = $start_d[0];
        $type = $start_d[1];
        //echo "type-".$type."fk-".$test_package_fk; exit; 
        $price = $this->input->post("test_add");

        if ($type == '1') {
            $data['query'] = $this->Branch_Model->master_get_insert("test_branch_price", array(
                "test_fk" => $test_package_fk, "branch_fk" => $id,
                "price" => $price, "type" => '1', "status" => '1'
            ));
        } else if ($type == '2') {
            $data['query'] = $this->Branch_Model->master_get_insert("test_branch_price", array(
                "test_fk" => $test_package_fk, "branch_fk" => $id,
                "price" => $price, "type" => '2', "status" => '1'
            ));
        }

        $this->session->set_flashdata("success", array("New Test Price successfully inserted."));
        redirect("Branch_Test_Price/edit_test_price/$id", "refresh");
    }

    function branch_test_export_csv($id) {

        $name = $this->input->get("test_name");
        $ttype = $this->input->get("ttype");

        if (!empty($id)) {

            if ($ttype == 2) {
                $q = "SELECT tp.id,tp.price,tp.test_fk,tp.branch_fk,tp.status,tm.title AS test_name, tp.r_code FROM package_master tm INNER JOIN test_branch_price tp ON tp.test_fk = tm.id   WHERE tp.branch_fk='$id' AND tm.status ='1' AND tp.`type`='2' and tp.status = '1' ";

                if ($name != "") {
                    $q .= "  AND tm.title LIKE '%$name%' ";
                }
            } else {
                $q = "SELECT tp.id,tp.price,tp.test_fk,tp.branch_fk,tp.status,tm.test_name, tp.r_code
                FROM test_master tm LEFT JOIN test_branch_price tp
                on tp.test_fk = tm.id 
                WHERE tp.branch_fk='$id' AND tm.status = 1 and tp.type='1' and tp.status = '1' ";

                if ($name != "") {
                    $q .= "  AND tm.test_name LIKE '%$name%'";
                }
            }

            $branch_wise_test = $this->Branch_Model->get_val($q);
            //echo "<pre>"; print_r($branch_wise_test); exit;

            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"branch_wise_test_price-" . date('d-M-Y') . ".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            $handle = fopen('php://output', 'w');
            $cnt = 0;
            fputcsv($handle, array("ID", "Branch Code", "Test Name", "Price", "R Code"));
            foreach ($branch_wise_test as $key) {
//                fputcsv($handle, array(++$cnt, $key->id, $key->branch_fk, $key->test_name, $key->price));
                fputcsv($handle, array($key->id, $key->branch_fk, $key->test_name, $key->price, $key->r_code));
            }
            fclose($handle);
            exit;
        }
    }

    public function isdeactive() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment(3);
        $mid = $this->uri->segment(4);
        $branch_id = $this->uri->segment(5);
                $name = $this->input->get("test_name");
        $ttype = $this->input->get("ttype");

        if ($tid == '0') {

            $data['query'] = $this->Branch_Model->master_tbl_update("test_branch_price", $mid, array("status" => '0'));
            $this->session->set_flashdata("success", array("Price successfull Inactivated."));
        } else {
            $data['query'] = $this->Branch_Model->master_tbl_update("test_branch_price", $mid, array("status" => '1'));
            $this->session->set_flashdata("success", array("Price successfull Activated."));
        }

        //redirect("Branch_Test_Price/edit_test_price/$branch_id", "refresh");
         redirect("Branch_Test_Price/edit_test_price/$branch_id?test_name=$name&ttype=$ttype&search=Search", "refresh");
    }

    function importprice_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');
        $this->load->library('csvimport');

        $branch_id = $this->uri->segment(3);
//        $ttype = $this->input->get("ttype");
//        $name = $this->input->get("test_name");
//        if ($ttype == 2) {
//
//            $q = "SELECT tp.id,tp.price,tp.test_fk,tp.branch_fk,tp.status,tm.title AS test_name 
//                                FROM package_master tm INNER JOIN test_branch_price tp ON tp.test_fk = tm.id   
//                                WHERE tp.branch_fk='$branch_id' AND tm.status ='1' AND tp.`type`='2'";
//
//            if ($name != "") {
//                $q .= "  AND tm.title LIKE '%$name%'";
//            }
//        } else {
//            $q = "SELECT tp.id,tp.price,tp.test_fk,tp.branch_fk,tp.status,tm.test_name
//                                FROM test_master tm LEFT JOIN test_branch_price tp
//                                on tp.test_fk = tm.id 
//                                WHERE tp.branch_fk='$branch_id' AND tm.status = 1 and tp.type='1'
//                                ";
//            if ($name != "") {
//                $q .= "  AND tm.test_name LIKE '%$name%'";
//            }
//        }
//        $branch_wise_test = $this->Branch_Model->get_val($q);
        //echo "<pre>"; print_r($branch_wise_test); exit;

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
            redirect("Branch_Test_Price/edit_test_price/$branch_id", "refresh");
        } else {
            $file_data = $this->upload->data();
            $file_path = './upload/csv/' . $file_data['file_name'];
            $cnt = 0;
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                $countall = count($csv_array);

                foreach ($csv_array as $row) {
                    $test_name = $row['Test Name'];
                    $id = trim($row['ID']);
                    $b_id = trim($row['Branch Code']);
                    $price = trim($row['Price']);
                    if ($branch_id == $b_id && is_numeric($price) && $price > 0) {
                        $update = $this->Branch_Model->master_tbl_update("test_branch_price", $id, array("price" => $price));
                    }
                }
            }

            $this->session->set_flashdata('success', array("File is uploaded successfully"));
            redirect("Branch_Test_Price/edit_test_price/$branch_id", "refresh");
        }
    }
function branch_doctor_test_list($city_fk = 1) {
	
	    $city_fk = $this->uri->segment(3);
        $branch_fk = $this->uri->segment(4);
        $doctor_fk = $this->uri->segment(5);
		$phone=$this->input->get("phone");
		
		$jobid=$this->input->get("jobid");
		$this->load->model('job_model');
		if($city_fk != "" && $branch_fk != "" && $doctor_fk != "" && $phone != ""){
			
		$test = $this->job_model->get_val("select IF(cut>0,cut,0) as cut,jobdiscount from branch_master where id='".$branch_fk."'");
        $cut=$test[0]["cut"];
		
		$test_for=$this->input->get("test_for");
		
		$testdiscount=array();
		$checkpackdis=array();
		
			
			$patirninfo=$this->job_model->get_val("SELECT id FROM `customer_master` WHERE `status`='1' AND active='1' AND `mobile`='" . $phone . "' ORDER BY id ASC LIMIT 1");
			
			if($patirninfo[0]["id"] != ""){
				
				$userid=$patirninfo[0]["id"];
				
		$papackdi=$this->job_model->get_val("SELECT bpd.id,bpd.`other_test_discount_family`,bpd.`other_test_discount_self` FROM patient_packdiscount pd INNER JOIN branch_package_discount bpd ON bpd.id=pd.`b_pdiscountid` WHERE pd.status='1' AND pd.`patient_id`='".$patirninfo[0]["id"]."' AND bpd.branch='".$branch_fk."' AND STR_TO_DATE(bpd.active_till_date,'%Y-%m-%d') >= '".date("Y-m-d")."' ORDER BY id DESC LIMIT 1");
		echo $this->db->last_query(); die();
		
		
		
if($papackdi[0] != ""){
			
			$self=$papackdi[0]["other_test_discount_self"];
			$otherpatient=$papackdi[0]["other_test_discount_family"];
			$packdisid=$papackdi[0]["id"];
			
		 if($test_for != ""){ $discount=$otherpatient; }else{ $discount=$self; }
		 
		  $doctest = $this->job_model->get_val("SELECT `test_fk`,`discount` FROM `branch_package_discount_test` WHERE STATUS='1' AND branch_package_discount_fk='$packdisid'");
		 foreach($doctest as $rowtest){
			 
			 $testdiscount[]=$rowtest["test_fk"];
			 $checkpackdis[$rowtest["test_fk"]]=$rowtest["discount"];
		 }
		 
		 
		 }else{
			 $doc_discount_check = $this->job_model->get_val("SELECT discount FROM `doctor_master` WHERE `status`='1' AND id='" . $doctor_fk . "'");
			$discount=$doc_discount_check[0]['discount'];
		 }	
			
			}else{
				$userid="";
				$doc_discount_check = $this->job_model->get_val("SELECT discount FROM `doctor_master` WHERE `status`='1' AND id='" . $doctor_fk . "'");
				$discount=$doc_discount_check[0]['discount'];
			}
		
		$selected = $this->input->get_post("selected");
        $selected_test = array();
        $selected_package = array();
        foreach ($selected as $key) {
            $a = explode("-", $key);
            if ($a[0] == 'p') {
                $selected_package[] = $a[1];
            } else {
                $selected_test[] = $a[1];
            }
        }
	$test = $this->job_model->get_val("SELECT 
  test_master.`id`,
  `test_master`.`test_name`,
  `test_master`.`PRINTING_NAME`,
  `test_master`.`description`,
  `test_master`.`SECTION_CODE`,
  `test_master`.`LAB_COST`,
  `test_master`.`status`,
  `test_branch_price`.`price`,
  t_tst AS sub_test,
  lab_doc_discount.`price` AS d_price 
FROM
  `test_master` 
  INNER JOIN `test_branch_price` 
    ON `test_master`.`id` = `test_branch_price`.`test_fk` and test_branch_price.type='1'
  LEFT JOIN 
    (SELECT 
      GROUP_CONCAT(tmm.`test_name` SEPARATOR '%@%') AS t_tst,
      tm.`id` 
    FROM
      `sub_test_master` 
      LEFT JOIN `test_master` tm 
        ON `sub_test_master`.`test_fk` = tm.`id` 
      LEFT JOIN test_master tmm 
        ON `sub_test_master`.`sub_test` = tmm.id 
    WHERE `sub_test_master`.`status` = '1' 
    GROUP BY tm.`id`) AS tst 
    ON tst.id = `test_master`.`id` 
    LEFT JOIN `lab_doc_discount` ON `lab_doc_discount`.`test_fk`=`test_master`.`id` and lab_doc_discount.lab_fk='" . $branch_fk . "' and lab_doc_discount.doc_fk='" . $doctor_fk . "' and lab_doc_discount.status='1'
WHERE `test_master`.`status` = '1' 
  AND `test_branch_price`.`status` = '1' 
  AND `test_branch_price`.`branch_fk` = '" .$branch_fk. "' 
GROUP BY `test_master`.`id`");

        $package = $this->job_model->get_val("SELECT `package_master`.id,`package_master`.title,
              `test_branch_price`.`price` AS `d_price1` FROM `package_master`
              INNER JOIN `test_branch_price`
              ON `package_master`.`id` = `test_branch_price`.`test_fk` and test_branch_price.type='2'
              WHERE `package_master`.`status` = '1'
              AND `test_branch_price`.`status` = '1' AND `package_master`.`is_active`='1' AND `test_branch_price`.`branch_fk` = '$branch_fk' ");
        $test_list = '<option value="">--Select Test--</option>';
foreach ($test as $ts) {
            if (!in_array($ts['id'], $selected_test)) {
				
				if($test_for == ""){

				if(in_array($ts['id'], $testdiscount)){
					
					$discounttest=$checkpackdis[$ts['id']];
					
					if ($ts['d_price'] > 0) {
                            $new_price = $ts["d_price"];
                        } else {
                            if ($discounttest > 0) {
                                $new_price = $ts["price"] - ($discounttest * $ts["price"] / 100);
                            } else {
                                if ($cut > 0) {
                                    $new_price = $ts["price"] - ($cut * $ts["price"] / 100);
                                } else {
                                    $new_price = $ts["price"];
                                }
                            }
                        }
                        $new_price = round($new_price);
					
				}else{
                
				if ($ts['d_price'] > 0) {
                            $new_price = $ts["d_price"];
                        } else {
                            if ($discount > 0) {
                                $new_price = $ts["price"] - ($discount * $ts["price"] / 100);
                            } else {
                                if ($cut > 0) {
                                    $new_price = $ts["price"] - ($cut * $ts["price"] / 100);
                                } else {
                                    $new_price = $ts["price"];
                                }
                            }
                        }
                        $new_price = round($new_price);
						
				}
				
				}else{
					
					
					if ($ts['d_price'] > 0) {
                            $new_price = $ts["d_price"];
                        } else {
                            if ($discount > 0) {
                                $new_price = $ts["price"] - ($discount * $ts["price"] / 100);
                            } else {
                                if ($cut > 0) {
                                    $new_price = $ts["price"] - ($cut * $ts["price"] / 100);
                                } else {
                                    $new_price = $ts["price"];
                                }
                            }
                        }
                        $new_price = round($new_price);
					
				}	
 
                $test_list .= ' <option value="t-' . $ts['id'] . '">' . ucfirst($ts['test_name']) . ' (Rs.' . $new_price . ')</option>';
            }
        }
        foreach ($package as $pk) {
            if (!in_array($pk['id'], $selected_package)) {
				$price=$pk['d_price1'];
				if($userid != ""){
				
$active_package = $this->job_model->get_val("SELECT `active_package`.id FROM
  `active_package` LEFT JOIN `package_master` ON `package_master`.`id` = `active_package`.`package_fk` WHERE `active_package`.`status` = '1' AND `due_to` >= '" . date("Y-m-d") . "' AND package_master.id='".$pk['id']."'  AND `active_package`.`user_fk` = '" . $userid . "' AND `active_package`.`parent`='0'");
  
            if (empty($active_package[0]["id"]) || $active_package[0]["id"]=="") {
                $price=$pk['d_price1'];
            }else{ $price=0; }
					
				}
                $test_list .= '<option value="p-'.$pk['id'].'">' . ucfirst($pk['title']) . ' (Rs.'.$price. ')</option>';
            }
        }

        echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data, "test_ary" => $test, "discount" => $discount));
		}else{
			
			$test_list = '<option value="">--Select Test--</option>';
			$refer="";
			$customer_data="";
			$test=array();
			$discount="";
			echo json_encode(array("refer" => $refer, "test_list" => $test_list, "customer" => $customer_data, "test_ary" => $test, "discount" => $discount));
			
		}
		
}
function update_code() {
    $id = $_POST['id'];
    $rcode = $_POST['r_code'];
    $update = $this->Branch_Model->master_tbl_update("test_branch_price", $id, array("r_code" => $rcode));
    echo 'id:' . $id . ',code:' . $rcode;
    exit;
}

    /* function branchtest_add(){

      $branchall=$this->registration_admin_model->get_val("SELECT id,city FROM `branch_master` WHERE STATUS='1'");
      foreach($branchall as $roe){
      $branch=$roe["id"];
      $city=$roe["city"];

      $this->db->query("INSERT INTO test_branch_price (test_fk,price,branch_fk,TYPE) SELECT  test_fk,price,'$branch','1' FROM  `test_master_city_price` WHERE STATUS='1' AND city_fk='$city'");

      $this->db->query("INSERT INTO test_branch_price (test_fk,price,branch_fk,TYPE) SELECT  package_fk,d_price,'$branch','2' FROM  `package_master_city_price` WHERE STATUS='1' AND city_fk='$city'");


      }

      } 
 function importtestprice_csv() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');


        $branch_id =89;

          
            $file_path = './upload/csv/kadiprice.csv';
            $cnt = 0;
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
				
				
                $countall = count($csv_array);
				$c=0;

                foreach ($csv_array as $row) {
                    
					$price = trim($row['price']);
					$testid = trim($row['id']);
					
					$this->Branch_Model->master_tbl_update_new("test_branch_price",array("test_fk"=>$testid,"branch_fk"=>'89',"status"=>'10',"type"=>'1'), array("price" => $price));
					
					$c++;
					
					
                }
				echo $c;
            }

        
    }*/	  
	/*  function importtestsample() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $this->load->library('csvimport');

            $file_path = './upload/csv/AIRMED_MASTER_12.csv';
            $cnt = 0;
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
				
				foreach ($csv_array as $row) {
                    
					
				$testid = trim($row['TestID']);
				$sample = trim($row['Specimen']);
				$cutoff = trim($row['TAT']);
				
				if($testid != ""){
				
				$this->Branch_Model->master_tbl_update_new("test_master",array("id"=>$testid),array("sample" =>$sample,"reporting"=>$cutoff));
				
				$cnt++;
				
				}
				
				}
				echo $cnt;
				
            }

        
    }  */
	  
}

?>
