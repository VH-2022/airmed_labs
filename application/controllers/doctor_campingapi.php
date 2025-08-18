<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class doctor_campingapi extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('camping_model');
    }
   function json_data($status, $error_msg, $data = NULL) {
        if ($data == NULL) {
            $data = array();
        }
        $final = array("status" => $status, "error_msg" => $error_msg, "data" => $data);
        return str_replace("null", '" "', json_encode($final));
    }
	
public function city_list() {
	    $data1 = $this->camping_model->master_fun_get_tbl_val("test_cities","city_fk as id,name",array("status" => "1"), array("name", "asc"));
        if (!empty($data1)) {
            echo $this->json_data("1", "", $data1);
        } else {
            echo $this->json_data("0", "no data available.", "");
        }
    }
public function camp_add(){
	
	    $name = $this->input->get_post("name");
		$city = $this->input->get_post("city");
		 $doctor = $this->input->get_post("doctor");
		 $remark = $this->input->get_post("remark");
		 $salesid = $this->input->get_post("salesid");
		 if($name != "" && $city != "" && $doctor != "" && $salesid != ""){
			 
			 $data = array(
                'name' => ucwords($name),
				'city_fk'=>$city,
				'created_by'=>$doctor,
				'remark'=>$remark,
                'createddate' =>date("Y-m-d H:i:s"),
				'sales_fk'=>$salesid,
				'created_by'=>$doctor,
            );

          $lastid=$this->camping_model->master_fun_insert('camping',$data);
		  
		   echo $this->json_data("1", "", array(array("id" => $lastid,"msg" => "Successfully")));
		  
		 }else{
			 
			  echo $this->json_data("0", "Parameter Not Passed.", "");
		 }
		  
    }
public function camp_list(){
	
	    $salesid = $this->input->get_post("salesid");
		if($salesid != ""){
			 
		 $data1 = $this->camping_model->get_val("SELECT c.id,c.name,c.remark,t.`city_name` AS cityname,d.`full_name` as doctorname FROM `camping` AS c LEFT JOIN city t ON t.`id`=c.`city_fk` LEFT JOIN doctor_master d ON d.`id`=c.`created_by` WHERE c.sales_fk = '$salesid' AND c.status = '1' ORDER BY c.name ASC");
		 if (!empty($data1)) {
            echo $this->json_data("1", "", $data1);
        } else {
            echo $this->json_data("0", "no data available.", "");
        }
		 }else{
			 
			  echo $this->json_data("0", "Parameter Not Passed.", "");
		 }
		  
    }	
public function camp_delete(){
	
	    $cid = $this->input->get_post("cid");
		 if($cid != ""){
			 
		 $data = array("status"=>'0',"updatddate"=>date("Y-m-d H:i:s"));

          $lastid=$this->camping_model->master_fun_update("camping",array("id"=>$cid), $data);
		  
		   echo $this->json_data("1", "", array(array("id" => $cid,"msg" => "Successfully")));
	
		 }else{
			 
			  echo $this->json_data("0", "Parameter Not Passed.", "");
		 }
		  
    }
public function camp_register(){
	
	    $campid = $this->input->get_post("campid");
	    $name = $this->input->get_post("name");
		 $age = $this->input->get_post("age");
		 $gender = $this->input->get_post("gender");
		 $mobile = $this->input->get_post("mobile");
		 $remark = $this->input->get_post("remark");
		 if($campid != "" && $name != "" && $age != "" && $gender != "" && $mobile != ""){
			 
			  $getdocid=$this->camping_model->fetchdatarow('created_by','camping',array("id"=>$campid));
			 
			 $data = array(
			  'camp_fk'=> $campid,
                'name' => ucwords($name),
				'mobile'=>$mobile,
				'gender'=>$gender,
				'age'=>$age,
				'remark'=>$remark,
				'created_by'=>$getdocid->created_by,
                'createddate' =>date("Y-m-d H:i:s")
            );

          $lastid=$this->camping_model->master_fun_insert('camping_register',$data);
		  
		   echo $this->json_data("1", "", array(array("id" => $lastid,"msg" => "Successfully")));
		  
		 }else{
			 
			  echo $this->json_data("0", "Parameter Not Passed.", "");
		 }
		  
    }
public function campregister_list(){
	
	    $campid = $this->input->get_post("campid");
		if($campid != ""){
			 
		 $data1 = $this->camping_model->get_val("SELECT cr.id,c.`name` AS campname,cr.`name`,cr.`mobile`,cr.`age`,cr.`gender`,cr.`remark` FROM camping_register cr LEFT JOIN camping c ON c.`id`=cr.`camp_fk` AND c.`status`='1' WHERE cr.`status`='1' and cr.camp_fk='$campid'");
        if (!empty($data1)) {
            echo $this->json_data("1", "", $data1);
        } else {
            echo $this->json_data("0", "no data available.", "");
        }
		 }else{
			 
			  echo $this->json_data("0", "Parameter Not Passed.", "");
		 }
		  
    }	
public function campregister_delete(){
	
	    $cid = $this->input->get_post("crid");
		 if($cid != ""){
			 
		 $data = array("status"=>'0',"updatddate"=>date("Y-m-d H:i:s"));

          $lastid=$this->camping_model->master_fun_update("camping_register",array("id"=>$cid), $data);
		  
		   echo $this->json_data("1", "", array(array("id" => $cid,"msg" => "Successfully")));
	
		 }else{
			 
			  echo $this->json_data("0", "Parameter Not Passed.", "");
		 }
		  
    }
function search_doctor() {
	
		 
        $search = $this->input->get_post('search');
        $test_city = $this->input->get_post('test_city');
        $data1 = $this->camping_model->get_val("SELECT * from test_cities  WHERE status='1' and id=' $test_city'");
		
		
        $data = $this->camping_model->get_val("SELECT 
`doctor_master`.id,  
`doctor_master`.`full_name`,
  `doctor_master`.mobile,
  `sales_speciality_master`.`name` AS speciality,
  `state`.`state_name`,
  `city`.`city_name`,
  IF(`doctor_master`.`mobile` LIKE '%" . $search . "%'='',`doctor_master`.`full_name`,`doctor_master`.mobile) AS search_result
FROM
  doctor_master 
  LEFT JOIN `sales_speciality_master` 
    ON `sales_speciality_master`.`id` = `doctor_master`.`speciality` 
    LEFT JOIN `state` ON `state`.`id`=`doctor_master`.`state`
    LEFT JOIN `city` ON city.`id`=`doctor_master`.`city`
WHERE `doctor_master`.`status` = '1' AND `doctor_master`.`city` = '" . $data1[0]["city_fk"] . "'
  AND (
    `doctor_master`.`full_name` LIKE '%" . $search . "%' 
    OR `doctor_master`.`mobile` LIKE '%" . $search . "%'
  )");
        if (!empty($data)) {
            echo $this->json_data("1", "", $data);
        } else {
            echo $this->json_data("0", "Data not found", "");
        }
    }	

}

?>
