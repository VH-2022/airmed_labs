<?php

class LabGroup_List extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('LabGroup_model');
        
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

   
    public function labgroup_add() { 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();

        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);

        $data['success'] = $this->session->flashdata("success");

        $this->load->library('form_validation');
        $this->form_validation->set_rules('labid','Lab Name','trim|required');
		$this->form_validation->set_rules('branch','branch','trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');


        if ($this->form_validation->run() != FALSE) {

            $data = array(
			   'branch_fk' =>$this->input->post('branch'),
                'labid' => $this->input->post('labid'),
                'status' =>1,
                "created_date" => date("Y/m/d H:i:s"),
                "created_by" => $data["login_data"]['id']
            );

           $data['query'] = $this->LabGroup_model->master_get_insert("b2b_labgroup", $data);

          $this->session->set_flashdata("success", array("Lab group Successfully Added."));
            redirect("LabGroup_List/labgroup_list", "refresh");
           }else{ show_404(); }
        
    }

  public function labgroup_list() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
   
        $type = $data["login_data"]['type'];
        $id = $data["login_data"]['id'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

       
          $totalRows = $this->LabGroup_model->num_row('b2b_labgroup', array('status' => 1));
        
          $config = array();
          $config["base_url"] = base_url() . "LabGroup_List/labgroup_list/";

            $config["total_rows"] =$totalRows;

            $config["per_page"] = 50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
             
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["page"] = $page;

            $data["query"] = $this->LabGroup_model->labselect_list($config["per_page"], $page);

                     $data["links"] = $this->pagination->create_links();
					 
			$data['branch_list']=$this->LabGroup_model->get_val("SELECT id,branch_name FROM branch_master WHERE status='1' order by branch_name ASC");
			
           /*  $data['lab_list'] = $this->LabGroup_model->get_val("select id,name from collect_from where id NOT IN (SELECT labid FROM b2b_labgroup where status='1') And  status ='1' order by name ASC"); */
           
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('labgroup_list', $data);
        $this->load->view('footer');
    }
    
   
   public function labgroup_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('3');
        $data['query'] = $this->LabGroup_model->master_get_update("b2b_labgroup", array("id" => $eid), array("status" => "0"), array("id", "desc"));

        $this->session->set_flashdata("success", array("Lab group Successfully Deleted."));
        redirect("LabGroup_List/labgroup_list", "refresh");
    }
public function getblab(){
	if (!is_loggedin()) {
            redirect('login');
        }
	 $branch=$this->input->get('brach');
	$lab_list = $this->LabGroup_model->get_val("select id,name from collect_from where id NOT IN (SELECT labid FROM b2b_labgroup where status='1' and branch_fk='$branch') And  status ='1' order by name ASC"); 
$barch="";
$barch.='<div class="form-group"><label for="recipient-name" class="control-label">Lab<i style="color:red;">*</i>:</label><select name="labid" class="form-control" required=""><option value="">Select Lab</option>';
foreach($lab_list as $lab){
	$labid=$lab['id'];
	$labname=$lab['name'];
	$barch.="<option value='$labid'>$labname</option>";
}
$barch.='</select><span style="color:red;"></span></div>';
echo $barch;
}

}

?>