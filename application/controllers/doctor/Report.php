<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends CI_Controller {

   function __construct() {
        parent::__construct();
   
         $logincheck=is_doctorlogin();
        if (!$logincheck){
            redirect('doctor');
        }else{
			
			 $this->load->model('doctor/doctor_model');
			 $docpart=$this->doctor_model->fetchdatarow("app_permission",'doctor_master',array("id"=>$logincheck["id"],"status"=>'1'));
			 $this->data['permission'] =$docpart->app_permission;
		}
		
        //$this->app_track();
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

    function index() {
		
        $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		
		$user_fk =$did;
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        $type = $this->input->get('type');
		$regid = $this->input->get('regid');
		$pname = $this->input->get('pname');
		if(!empty($pname))
			$pname = strtoupper(trim($pname));

		if($from != "" && $to != ""){
		
		$data["from"]=$from;
		$data["to"]=$to;
		$data["regid"]=$regid;
		$data["pname"]=$pname;
		$from=date("Y-m-d",strtotime($from));
        $to= date("Y-m-d",strtotime($to));
			
		$this->load->library('curl');
		$baseurl=base_url();
		$json=$this->curl->simple_get("$baseurl/doctor_api/doctor_customer_job?user_id=$user_fk&from=$from&to=$to&type=$type&regid=$regid&pname=$pname");
		$array = json_decode( $json, true );		
        $data["querydata"]=$array["data"];
		/* 	
			echo "<pre>";
			print_r($data["querydata"]);die();  */
		
		}else{ $data["from"]="";
		$data["to"]="";	 
		$data["querydata"]=array(); }
		
		
		$this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/d_report', $data);
        $this->load->view('doctor/d_footer');
        
    }
 function getjobdetils(){
	 	
        $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		
		$user_fk =$did;
        $job_id = $this->input->get('job_id');
        
		if($job_id != ""){
			
		$this->load->library('curl');
		$baseurl=base_url();
		$json=$this->curl->simple_get("$baseurl/doctor_api/doctor_view_report?job_id=$job_id");
		$array = json_decode( $json, true );
        $querydata=$array["data"];
		
		if($querydata != null){ if($querydata[0]["report"] != " " && $querydata[0]["report"] != null){ ?><a  target="_blank" href="<?= base_url()."upload/report/".$querydata[0]["report"]; ?>" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Report</a> <?php } } ?>
		<table class="table table-striped" >
			<thead>
			<tr>
			<th>Test/Package Name</th>
			<th>Price</th>
			<th>Date</th>
			<th>Status</th>
			</tr>
			</thead>
                <tbody>
				<?php if($querydata != null){
				foreach($querydata as $row){

				?>
				<tr>
				<td><?= $row["test_name"]; ?></td>
				<td><?= $row["price"]; ?></td>
				<td><?= date("d-m-Y",strtotime($row["created_date"])); ?></td>
				<td>
				<?php if ($row['status'] == 1) {
                                                        echo "<span class='label label-danger '>Waiting For Approval</span>";
                                                    }
                                                    if ($row['status'] == 6) {
                                                        echo "<span class='label label-warning '>Approved</span>";
                                                    }
                                                    if ($row['status'] == 7) {
                                                        echo "<span class='label label-warning '>Sample Collected</span>";
                                                    }
                                                    if ($row['status'] == 8) {
                                                        echo "<span class='label label-warning '>Processing</span>";
                                                    }
                                                    if ($row['status'] == 2) {
                                                        echo "<span class='label label-success '>Completed</span>";
                                                    }
                                                    if ($row['status'] == 0) {
                                                        echo "<span class='label label-danger '>User Deleted</span>";
                                                    } ?>
				</td>
				</tr>
					<?php } }else{
					?><tr><td colspan="4">No records found</td></tr> <?php 
				} ?>
			</tbody>
            </table>
			
		<?php  
		
		}
		
		
		
        
    }	



}
