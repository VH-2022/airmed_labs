<?php

class Bank_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Bank_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

   function bank_list() { 
       
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $type=$data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        // $reg_id = $this->input->get('search');
        // $unit_name = $this->input->get('unit_name');
        $bank_name = $this->input->get('bank_name');
        $branch_name = $this->input->get('branch_name');
        $account_no = $this->input->get('account_no');
        $city = $this->input->get('city');
		$ifc_code = $this->input->get('ifc_code');

       if ($bank_name !='' || $branch_name !='' || $ifc_code !='' || $account_no !='' ||   $city !='') {
		   
              $srchdata = array("bank_name"=>$bank_name,"branch_name"=>$branch_name,"ifc_code"=>$ifc_code,"account_no"=>$account_no,"city"=>$city);
            $data['bank_name'] = $bank_name;
             $data['branch_name'] = $branch_name;
             $data['ifc_code'] = $ifc_code;
              $data['account'] = $account_no;
               $data['city'] = $city;
            $totalRows = $this->Bank_model->getitem_num($srchdata);
            $config = array();
            /* Vishal Code Start */
            $config["base_url"] = base_url() . "inventory/bank_master/bank_list?bank_name=$bank_name&branch_name=$branch_name&ifc_code=$ifc_code&account_no=$account_no&city=$city";
            $config["total_rows"] = $totalRows;
            /* Vishal Code End */
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $sort = $this->input->get("sort");
            $by = $this->input->get("by");
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
            $data["query"] = $this->Bank_model->getitem($srchdata, $config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
			
        } else {
            $srchdata = array();
            $totalRows = $this->Bank_model->getitem_num($srchdata);
            $config = array();
            $config["base_url"] = base_url() . "inventory/bank_master/bank_list/";
            $config["total_rows"] = $totalRows;
            $config["per_page"] = 100;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $data["query"] = $this->Bank_model->getitem($srchdata, $config["per_page"], $page);
          
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
     if($type =='8'){
 $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/bank_list', $data);
        $this->load->view('inventory/footer');
     }else{
         $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('inventory/bank_list', $data);
        $this->load->view('footer');
     }
       
    }

    public function add() { 
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
         $type=$data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
       $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
         $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
          $this->form_validation->set_rules('account_no', 'Account No', 'trim|required|numeric|xss_clean|min_length[11]|max_length[16]');
          $this->form_validation->set_rules('city', 'City Name', 'trim|required');
        
        
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            
            $data = array(
                'bank_name' => $this->input->post('bank_name'),
                'branch_name' => $this->input->post('branch_name'),
                'account_no' => $this->input->post('account_no'),
				'ifc_code'=>$this->input->post('ifc_code'),
                'city' => $this->input->post('city'),
                 'remark' => $this->input->post('remark'),
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id'],
                "status"=>"1" 
            );
            $data['query'] = $this->Bank_model->master_get_insert("inventory_bank", $data);
            
            $this->session->set_flashdata("success", array("Bank Name  Successfull Added."));
            redirect("inventory/Bank_master/bank_list", "refresh");
        } else {
            
             if($type =='8'){
 $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/bank_add', $data);
        $this->load->view('inventory/footer');
     }else{
         $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('inventory/bank_add', $data);
        $this->load->view('footer');
     }
        }
    }

    public function edit() { 
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
         $type=$data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('4');
        $ids = $data["eid"];
      
        $this->load->library('form_validation');
 $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
         $this->form_validation->set_rules('branch_name', 'Branch Name', 'trim|required');
          $this->form_validation->set_rules('account_no', 'Account No', 'trim|required|numeric|xss_clean|min_length[11]|max_length[16]');
          $this->form_validation->set_rules('city', 'City Name', 'trim|required');

     
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $id = $data["login_data"]['id'];
        if ($this->form_validation->run() != FALSE) {
           
           $post['bank_name'] = $this->input->post('bank_name');
            $post['branch_name'] = $this->input->post('branch_name');
            $post['account_no'] = $this->input->post('account_no');
            $post['city'] = $this->input->post('city');
             $post['remark'] = $this->input->post('remark');
            $post['status'] = 1;
            $post['modified_by'] = $data["login_data"]['id'];
			$post['ifc_code']=$this->input->post('ifc_code');
      
            $data['query'] = $this->Bank_model->master_get_update("inventory_bank", array('id' =>  $ids), $post);
            $this->session->set_flashdata("success", array("Bank Successfull Updated."));
            redirect("inventory/Bank_master/bank_list", "refresh");
        } else {
            $data['query'] = $this->Bank_model->master_get_tbl_val("inventory_bank", array("id" => $data["eid"]), array("id", "desc"));
            
          if($type =='8'){
 $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/bank_edit', $data);
        $this->load->view('inventory/footer');
     }else{
         $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('inventory/bank_edit', $data);
        $this->load->view('footer');
     }
        }
    }

    public function bank_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        $data['query'] = $this->Bank_model->master_get_update("inventory_bank", array("id" => $eid), array("status" => "0"), array("id", "desc"));

        $this->session->set_flashdata("success", array("Bank  Successfully Deleted."));
        redirect("inventory/Bank_master/bank_list", "refresh");
    }
    public function duplicate_name(){
    $bank = $this->input->post('bank_name');

     $query = $this->Bank_model->check_dup($bank);

     if($query > 0){
          $this->form_validation->set_message(
                    'duplicate_name', 'Bank Name All ready Exist'
            );
            return FALSE;
     }else{ 
         return TRUE;
     }
 }
  
}

?>