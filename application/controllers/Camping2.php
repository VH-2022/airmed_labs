<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Camping extends CI_Controller {

   function __construct() {
        parent::__construct();
   
        //$this->app_track();
		$this->load->model('user_model');
    }

    function app_track() {
        $this->load->library("Util");
        $util = new Util();
        $util->app_track();
    }

  public function index($docid=null){
		
     if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$adminid=$data["login_data"]["id"];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		if($docid != ""){
		
		
		$this->load->model('doctor/camping_from_model');
		
		$data["dockdetils"]=$this->camping_from_model->fetchdatarow('id,full_name',"doctor_master",array("id"=>$docid));
		
		$did=$docid;
		$this->load->library("form_validation");
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if($this->form_validation->run() != FALSE) {
			
		$getd_city=$this->camping_from_model->fetchdatarow('city,ref_id',"doctor_master",array("id"=>$did));
			$data = array(
                'name' => ucwords($this->input->post('name')),
				'city_fk'=>$getd_city->city,
				'remark'=>$this->input->post('remark'),
                'createddate' => date("Y-m-d H:i:s"),
				'created_by'=>$did,
				'sales_fk'=>$getd_city->ref_id,
				'adminfk'=>$adminid,
				'addedtype'=>3
            );
			
            $this->camping_from_model->master_fun_insert('camping', $data);
			$this->session->set_flashdata('success',"Data Successfully Added");
			
            redirect("camping/index/$docid");
			
		}else{
		
		$cfname = $this->input->get('cfname');
		$this->load->library("pagination");
         $data['cfname'] = $cfname;
       	
            $totalRows = $this->camping_from_model->num_row($cfname,$did);
            $config = array();
            
            $config["base_url"] = base_url()."camping";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->camping_from_model->search($cfname,$did,$config["per_page"], $page);
			$i=0;
			foreach($data['query'] as $row){
			
			switch ($row["addedtype"]) {
    case 1:
	
	$getaddby=$this->camping_from_model->fetchdatarow('first_name,last_name',"sales_user_master",array("id"=>$row["sales_fk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->first_name." ".$getaddby->last_name);

        break;
    case 2:
	$getaddby=$this->camping_from_model->fetchdatarow('full_name',"doctor_master",array("id"=>$row["created_by"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->full_name);
        break;
    case 3:
	$getaddby=$this->camping_from_model->fetchdatarow('name',"admin_master",array("id"=>$row["adminfk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->name);

        break;
		default:
		$data['query'][$i]["addedby"]="";
}
	$i++;
			}

            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
			$data["pagename"]="Talk camp";
			$data["camptype"]="1";
			
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('d_sample_from_list', $data);
        $this->load->view('footer');
		
		}
		
		}else{ show_404(); }
        
    }
public function edit($id){

       if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$adminid=$data["login_data"]["id"];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		$this->load->model('doctor/camping_from_model');
		
		
if($id != ""){
	
	$data["query"]=$this->camping_from_model->fetchdatarow('id,name,type,created_by',"camping",array("id"=>$id,"status !="=>'0'));
	if($data["query"] != ""){
		
		$camptype=$data["query"]->type; 
		$did=$data["query"]->created_by; 
		
		$data["dockdetils"]=$this->camping_from_model->fetchdatarow('id,full_name',"doctor_master",array("id"=>$did));
		
	$this->load->library("form_validation");
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		
        if($this->form_validation->run() != FALSE) {
			
			$data = array('name' => $this->input->post('name'));
			$this->camping_from_model->master_fun_update('camping',array("id"=>$id), $data);
			
			$this->session->set_flashdata('success', "Data successfully updated");
			if($camptype==2){ redirect("camping/society/$did"); }else{ redirect("camping/index/$did"); }
            
		}else{
			
			if($camptype==2){ $data["pagename"]="Society Camping";
			$data["camptype"]="2"; }else{ $data["pagename"]="Talk Camping";
			$data["camptype"]="1"; }
			
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('d_samplefrom_edit', $data);
        $this->load->view('footer');
		
		}	
	}else{
		show_404();
	}
		
}else{

show_404();	
	
}
	
	
}
public function campingstatus(){

 if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$adminid=$data["login_data"]["id"];
		$this->load->model('doctor/camping_from_model');
		
	
	$pcid=$this->uri->segment('4');
	$query= $this->camping_from_model->fetchdatarow("id,status,created_by","camping",array("id" =>$pcid,"status !="=>'0'));
	
	if($query != ""){
	$did=$query->created_by;
	
	if($query->status==2){ $data = array('status' => '1');  $this->session->set_flashdata('success', 'Successfully Sample From Activated'); }else{ $data = array('status' => '2'); $this->session->set_flashdata('success', 'Successfully Sample From Deactivated'); }

        $this->camping_from_model->master_fun_update('camping',array("id"=>$pcid),$data);
		 
        redirect("doctor/camping/$did");
	
	}else{ show_404(); }
}
    public function delete() {
		 
	 if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$this->load->model('doctor/camping_from_model');
		
		$pcid = $this->uri->segment('3');
		$query= $this->camping_from_model->fetchdatarow("id,status,type,created_by","camping",array("id" =>$pcid,"status !="=>'0'));
		
		if($query != ""){
			
		 $did=$query->created_by;
			
        $data = array(
            'status' => '0');

        $this->camping_from_model->master_fun_update('camping',array("id"=>$pcid),$data);
		
		$camptype=$query->type; 
		$this->session->set_flashdata('success', 'Data Successfully Deleted');
		
       if($camptype==2){ redirect("camping/society/$did"); }else{ redirect("camping/index/$did"); }
	   
		}else{ show_404(); }
    }	
 
public function register($cid=null) {
		 if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$adminid=$data["login_data"]["id"];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		$this->load->model('doctor/camping_from_model');
		
	if($cid != ""){
			
	$data["campdatils"]= $this->camping_from_model->fetchdatarow("id,name,status,type,city_fk,created_by","camping",array("id" =>$cid,"status"=>'1'));
	
	if($data["campdatils"] != ""){
		
		$camptype=$data["campdatils"]->type;
		$did=$data["campdatils"]->created_by;
		
		 $this->load->library("form_validation");
		 $this->form_validation->set_rules('pname', 'Patient name', 'trim|required');
		 $this->form_validation->set_rules('mobile', 'Mobile no', 'trim|required|numeric|min_length[10]|max_length[13]');
		 $this->form_validation->set_rules('paintage', 'Patient Age', 'trim|required|numeric|greater_than[0]|max_length[3]');
		 $this->form_validation->set_rules('pgender', 'Gender', 'trim|required|numeric');
		 $this->form_validation->set_rules('remark', 'Remark', 'trim');
		 if($camptype==2){
			 $this->form_validation->set_rules('testall[]', 'Test/Package', 'trim|required');
		 }
       /*   $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>'); */
        
		if($this->form_validation->run() != FALSE) {
			
			
		$name = $this->input->post("pname");
		 $age = $this->input->post("paintage");
		 $gender = $this->input->post("pgender");
		 $mobile = $this->input->post("mobile");
		 $remark = $this->input->post("remark");
		  
		 $testall=$this->input->post("testall");
		 
			$data = array(
			  'camp_fk'=> $cid,
                'name' => ucwords($name),
				'mobile'=>$mobile,
				'gender'=>$gender,
				'age'=>$age,
				'remark'=>$remark,
				'created_by'=>$did,
				'adminfk'=>$adminid,
				'addedtype'=>3,
                'createddate' =>date("Y-m-d H:i:s")
            );

            $camprgis=$this->camping_from_model->master_fun_insert('camping_register', $data);
			if($camptype==2){
				foreach ($testall as $key) {
					$data=
                        $tn = explode("-", $key);
						
                        if ($tn[0] == 't') {
							$datatest=array("campid"=>$cid,"campragister"=>$camprgis,"testid"=>$tn[1],"testtype"=>1,"creteddate"=>date("Y-m-d H:i:s"));
							
						}else if($tn[0] == 'p'){
		-					$datatest=array("campid"=>$cid,"campragister"=>$camprgis,"testid"=>$tn[1],"testtype"=>2,"creteddate"=>date("Y-m-d H:i:s"));
						}
						
						$this->camping_from_model->master_fun_insert('camping_test',$datatest);
					
				}
				
			
			}
			$this->session->set_flashdata('success', "Data Successfully Added");
            
			redirect("camping/register/$cid");
			
		}else{
		
		$cfname = $this->input->get('cfname');
		$this->load->library("pagination");
        $data['cfname'] = $cfname;
		
		$data["dockdetils"]=$this->camping_from_model->fetchdatarow('id,full_name',"doctor_master",array("id"=>$did));
        
            $totalRows = $this->camping_from_model->camprragister_num($did,$cid,$cfname);
			
            $config = array();
           
            $config["base_url"] = base_url() . "doctor/camping/register/".$cid."";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 200;
            $config['page_query_string'] = TRUE;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->camping_from_model->get_camprragister($did,$cid,$cfname,$config["per_page"], $page);
			
			if($camptype==2){
				$i=0;
				foreach($data['query'] as $valu){
					$camptest=array(); 
					$getalltest=$this->camping_from_model->get_val("SELECT testid,`testtype` FROM camping_test WHERE status='1' and campragister='".$valu["id"]."'");
					
					foreach($getalltest as $ctest){
						$testid=$ctest["testid"];
						$testtype=$ctest["testtype"];
						if($ctest["testtype"]==2){
						 $gettest=$this->camping_from_model->fetchdatarow("title as test_name","package_master",array("id" =>$ctest["testid"]));
						 
						 $camptest[]=$gettest->test_name;
						
						}else{
							
							$gettest=$this->camping_from_model->fetchdatarow("test_name","test_master",array("id" =>$ctest["testid"]));
							$camptest[]=$gettest->test_name;
							
						}
						
						
					}
					$teststring=implode(",<br>",$camptest);
					switch ($valu["addedtype"]) {
    case 1:
	
	$getaddby=$this->camping_from_model->fetchdatarow('name',"phlebo_master",array("id"=>$valu["sales_fk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->name);

        break;
    case 2:
	$getaddby=$this->camping_from_model->fetchdatarow('full_name',"doctor_master",array("id"=>$valu["created_by"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->full_name);
        break;
    case 3:
	$getaddby=$this->camping_from_model->fetchdatarow('name',"admin_master",array("id"=>$valu["adminfk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->name);

        break;
		default:
		$data['query'][$i]["addedby"]="";
}
					$data['query'][$i]["test"]=$teststring;
					$i++;
					
				}
				
			$data["pagename"]="Society Camp";
			$data["camptype"]="2"; 
			
			$campcity=$data["campdatils"]->city_fk;
			$testcity= $this->camping_from_model->fetchdatarow("id,city_fk","test_cities",array("city_fk" =>$campcity,"status"=>'1'));
			$city=$testcity->id;
			$data['test'] = $this->camping_from_model->get_val("select t.test_name,t.id from test_master t inner JOIN test_master_city_price p ON t.id = p.test_fk AND p.city_fk='$city'  where t.status='1'  GROUP BY t.id");

			$data['packges'] = $this->camping_from_model->get_val("select t.title as test_name,t.id from package_master t inner JOIN package_master_city_price p ON t.id = p.package_fk AND p.city_fk='$city'  where t.status='1'  GROUP BY t.id");
			
			
			}else{ 
			
			$i=0;
			foreach($data['query'] as $row){
				
			
			switch ($row["addedtype"]) {
    case 1:
	
	$getaddby=$this->camping_from_model->fetchdatarow('first_name,last_name',"sales_user_master",array("id"=>$row["sales_fk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->first_name." ".$getaddby->last_name);

        break;
    case 2:
	$getaddby=$this->camping_from_model->fetchdatarow('full_name',"doctor_master",array("id"=>$row["created_by"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->full_name);
        break;
    case 3:
	$getaddby=$this->camping_from_model->fetchdatarow('name',"admin_master",array("id"=>$row["adminfk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->name);

        break;
		default:
		$data['query'][$i]["addedby"]="";
}
	$i++;
			}
			
			$data["pagename"]="Talk Camp";
			$data["camptype"]="1"; 
			
			}
			
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
			
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('d_campingragisterviews', $data);
        $this->load->view('footer');
		
		
		}
		
	}else{ show_404(); }
		
		}else{ show_404(); }
        
    }
	 public function campregisterdelete() {
		 
		 if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		$this->load->model('doctor/camping_from_model');
		
		 
		$pcid = $this->uri->segment('4');
		$campid = $this->uri->segment('3'); 
		if($pcid != "" && $campid  != ""){
		
        
		$query= $this->camping_from_model->fetchdatarow("id,status,created_by","camping_register",array("id" =>$pcid,"status !="=>'0'));
		
		if($query != ""){
			$did=$query->created_by;
			
        $data = array(
            'status' => '0');

        $this->camping_from_model->master_fun_update('camping_register',array("id"=>$pcid),$data);
		
        $this->session->set_flashdata('success', 'Data Successfully Deleted');
        redirect("camping/register/$campid");
		}else{ show_404(); }
		}else{ show_404(); }
    }
public function campragister_csv(){
	
     if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
		$this->load->model('doctor/camping_from_model');

$cid=$this->input->get('cid');
		
	if($cid != "" ){
		
	$campdatils= $this->camping_from_model->fetchdatarow("id,type,created_by","camping",array("id" =>$cid,"status"=>'1'));
	
	if($campdatils != ""){
		$camptype=$campdatils->type;
		$did=$campdatils->created_by;
		$cfname="";
      
	    $query = $this->camping_from_model->get_camprragister($did,$cid,$cfname,5000,0);
			
		header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"campregister.csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
		if($camptype==2){
			
		fputcsv($handle, array("Srl no", "Name", "Mobile", "Age", "Gender","Test/Package","Remark","Added by","Date"));
		
		}else{
        fputcsv($handle, array("Srl no", "Name", "Mobile", "Age", "Gender","Remark","Added by","Date")); 
		}
        $i = 0;
			foreach($query as $row){ $i++;
			if( $row['gender']==1){ $gender ="Male"; }else{ $gender ="Female"; }
			if($camptype==2){
				$camptest=array(); 
					$getalltest=$this->camping_from_model->get_val("SELECT testid,`testtype` FROM camping_test WHERE status='1' and campragister='".$row["id"]."'");
					
					foreach($getalltest as $ctest){
						$testid=$ctest["testid"];
						$testtype=$ctest["testtype"];
						if($ctest["testtype"]==2){
						 $gettest=$this->camping_from_model->fetchdatarow("title as test_name","package_master",array("id" =>$ctest["testid"]));
						 
						 $camptest[]=$gettest->test_name;
						
						}else{
							
							$gettest=$this->camping_from_model->fetchdatarow("test_name","test_master",array("id" =>$ctest["testid"]));
							$camptest[]=$gettest->test_name;
							
						}
						
					}
					$teststring=implode(",",$camptest);
					
					switch ($row["addedtype"]) {
    case 1:
	
	$getaddby=$this->camping_from_model->fetchdatarow('first_name,last_name',"sales_user_master",array("id"=>$row["sales_fk"]));
	$addedby=ucwords($getaddby->first_name." ".$getaddby->last_name);

        break;
    case 2:
	$getaddby=$this->camping_from_model->fetchdatarow('full_name',"doctor_master",array("id"=>$row["created_by"]));
	$addedby=ucwords($getaddby->full_name);
        break;
    case 3:
	$getaddby=$this->camping_from_model->fetchdatarow('name',"admin_master",array("id"=>$row["adminfk"]));
	$addedby=ucwords($getaddby->name);

        break;
		default:
		$addedby="";
}
					
					
					fputcsv($handle, array($i,$row['name'],$row['mobile'],$row['age'],$gender,$teststring,$row['remark'],$addedby,date("d-m-Y",strtotime($row['createddate']))));
				
			}else{
				
			switch ($row["addedtype"]) {
    case 1:
	
	$getaddby=$this->camping_from_model->fetchdatarow('first_name,last_name',"sales_user_master",array("id"=>$row["sales_fk"]));
	$addedby=ucwords($getaddby->first_name." ".$getaddby->last_name);

        break;
    case 2:
	$getaddby=$this->camping_from_model->fetchdatarow('full_name',"doctor_master",array("id"=>$row["created_by"]));
	$addedby=ucwords($getaddby->full_name);
        break;
    case 3:
	$getaddby=$this->camping_from_model->fetchdatarow('name',"admin_master",array("id"=>$row["adminfk"]));
	$addedby=ucwords($getaddby->name);

        break;
		default:
		$addedby="";
}	
            fputcsv($handle, array($i,$row['name'],$row['mobile'],$row['age'],$gender,$row['remark'],$addedby,date("d-m-Y",strtotime($row['createddate']))));
			}
			
			}
			fclose($handle);
        exit;
		
	}else{ show_404(); }
		
		}else{ show_404(); }

}
public function society($docid=null) {
		
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
		
		if($docid != ""){
			
		$this->load->model('doctor/camping_from_model');
		$adminid=$data["login_data"]["id"];
		$data["dockdetils"]=$this->camping_from_model->fetchdatarow('id,full_name',"doctor_master",array("id"=>$docid));
		
		
		$did=$docid;
		$this->load->library("form_validation");
		 $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if($this->form_validation->run() != FALSE) {
			
			$getd_city=$this->camping_from_model->fetchdatarow('city,ref_id',"doctor_master",array("id"=>$did));
			
			$data = array(
                'name' => ucwords($this->input->post('name')),
				'city_fk'=>$getd_city->city,
				'remark'=>$this->input->post('remark'),
				'type'=>2,
                'createddate' => date("Y-m-d H:i:s"),
				'created_by'=>$did,
				'adminfk'=>$adminid,
				'addedtype'=>3
            );
			
            $this->camping_from_model->master_fun_insert('camping', $data);
			$this->session->set_flashdata('success', "Data Successfully Added");
			redirect("camping/society/$did");
			
		}else{
		
		$cfname = $this->input->get('cfname');
		$this->load->library("pagination");
         $data['cfname'] = $cfname;
       	
            $totalRows = $this->camping_from_model->soccitynum_row($cfname,$did);
            $config = array();
            
			$config["base_url"] = base_url()."camping/society?cfname=".$cfname;
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 50;
            $config['page_query_string'] = TRUE;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data['query'] = $this->camping_from_model->scocitysearch($cfname,$did,$config["per_page"], $page);
			
			$i=0;
			foreach($data['query'] as $row){
			
			switch ($row["addedtype"]) {
    case 1:
	
	$getaddby=$this->camping_from_model->fetchdatarow('first_name,last_name',"sales_user_master",array("id"=>$row["sales_fk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->first_name." ".$getaddby->last_name);

        break;
    case 2:
	$getaddby=$this->camping_from_model->fetchdatarow('full_name',"doctor_master",array("id"=>$row["created_by"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->full_name);
        break;
    case 3:
	$getaddby=$this->camping_from_model->fetchdatarow('name',"admin_master",array("id"=>$row["adminfk"]));
	$data['query'][$i]["addedby"]=ucwords($getaddby->name);

        break;
		default:
		$data['query'][$i]["addedby"]="";
}
	$i++;
			}
			
			
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
			$data["pagename"]="Society camp";
			$data["camptype"]="2";
		
		$this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('d_sample_from_list', $data);
        $this->load->view('footer');
		
		}
		
		}
        
    }

}
