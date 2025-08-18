<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reception extends CI_Controller {

   function __construct() {
        parent::__construct();
   
       $logincheck=is_doctorlogin();
        if (!$logincheck){
            redirect('doctor');
        }else{
			
			$this->load->model('doctor/camping_from_model');
			 $docpart=$this->camping_from_model->fetchdatarow("app_permission",'doctor_master',array("id"=>$logincheck["id"],"status"=>'1'));
			/*  if($docpart->app_permission != '1'){  redirect('doctor/dashboard'); } */
			 $this->data['permission'] =$docpart->app_permission;
		}
		
        //$this->app_track();
    }

   

  public function index() {
		
        $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		$this->load->library("form_validation");
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('mobile', 'mobile', 'trim|required|numeric|min_length[10]|max_length[13]|callback_checkmobile');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
		
        if($this->form_validation->run() != FALSE) {
			
			$data=array("name"=>ucwords($this->input->post('name')),"mobile"=>$this->input->post('mobile'),"doctorfk"=>$did,"crerteddate"=>date("Y-m-d H:i:s"));
			
            $this->camping_from_model->master_fun_insert('doctor_reception', $data);
			$this->session->set_flashdata('success', "Data Successfully Added");
			
            redirect("doctor/reception");
			
		}else{
			
		$search = $this->input->get('search');
		$this->load->library("pagination");
        
            $totalrowsarray = $this->camping_from_model->get_val("SELECT COUNT(id) AS totalr FROM doctor_reception WHERE STATUS='1' and doctorfk='$did' And (name like '%$search%' or mobile like '%$search%') ");
			$totalRows=$totalrowsarray[0]["totalr"];
            $config = array();
            
            $config["base_url"] = base_url()."doctor/reception";
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
			$data['query'] = $this->camping_from_model->get_val("SELECT id,name,mobile,crerteddate FROM doctor_reception WHERE STATUS='1' and doctorfk='$did' And (name like '%$search%' or mobile like '%$search%') order by id desc limit $page,".$config["per_page"]."");
		
			}
            $data["links"] = $this->pagination->create_links();
            $data["pages"] = $page;
			
		$this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/receptionlist_view', $data);
        $this->load->view('doctor/d_footer');
		
		}
        
    
 function checkmobile($mobile) {
		$result = $this->camping_from_model->master_num_rows('doctor_reception',array("mobile"=>$mobile,"status !="=>'0'));
        if ($result !=0) {
            $this->form_validation->set_message('checkmobile', 'Mobile Number Already Exists!');
            return false;
        } else {
            return TRUE;
        }
   }
public function edit($id){

        $data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		
if($id != ""){
	
	$data["query"]=$this->camping_from_model->fetchdatarow('id,name,mobile,',"doctor_reception",array("id"=>$id,"doctorfk"=>$did,"status !="=>'0'));
	if($data["query"] != ""){
		
	$this->load->library("form_validation");
$this->form_validation->set_rules('name', 'Name', 'trim|required');
$this->form_validation->set_rules('mobile', 'mobile', 'trim|required|numeric|min_length[10]|max_length[13]|callback_checkmobile1');
		
        if($this->form_validation->run() != FALSE) {
		
			$data = array('name' => ucwords($this->input->post('name')),"mobile"=>$this->input->post('mobile'));
			
			$this->camping_from_model->master_fun_update('doctor_reception',array("id"=>$id), $data);
			
			$this->session->set_flashdata('success', "Data successfully updated");
			
			redirect("doctor/reception");
            
		}else{
		
		$data["pagename"]="Reception";
		$this->load->view('doctor/d_header');
        $this->load->view('doctor/d_nav', $data);
        $this->load->view('doctor/reception_edit_view', $data);
        $this->load->view('doctor/d_footer');

		}	
	}else{
		show_404();
	}
		
}else{

show_404();	
	
}
	
	
}
 function checkmobile1($mobile) {
	
	 $id=$this->uri->segment('4');
		 $result = $this->camping_from_model->master_num_rows('doctor_reception',array("mobile"=>$mobile,"id !="=>$id,"status !="=>'0'));
		
        if ($result !=0) {
            $this->form_validation->set_message('checkmobile1', 'Mobile Number Already Exists!');
            return false;
        } else {
            return TRUE;
        }
   }   
public function delete() {
		 
		$data["login_data"] = is_doctorlogin();
		$did=$data["login_data"]["id"];
		$pcid = $this->uri->segment('4');
		
		$query= $this->camping_from_model->fetchdatarow("id","doctor_reception",array("id" =>$pcid,"doctorfk"=>$did,"status !="=>'0'));
		
		if($query != ""){
		
        $data = array(
            'status' => '0');

        $this->camping_from_model->master_fun_update('doctor_reception',array("id"=>$pcid),$data);
		
		redirect("doctor/reception");
	   
		}else{ show_404(); }
    }	

}