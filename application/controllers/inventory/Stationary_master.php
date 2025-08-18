<?php

class Stationary_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Stationary_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $data["login_data"] = logindata();
    }

    function stationary_list() {
          if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $reg_id = $this->input->get('search');
           $city = $this->input->get('city_fk');
        $unit_name = $this->input->get('unit_name');
        $brand_name = $this->input->get('brand_name');
        $hsn_code = $this->input->get('hsn_code');
        $type1 = $this->input->get('type1');
        
        $config = array();
       
            
             $config["per_page"] =50;
            $config["uri_segment"] = 4;
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
            $config['page_query_string'] = TRUE;
             $temp ="";
          if ($reg_id != '') {
            $temp .= ' AND it.reagent_name LIKE "' . $reg_id . '"';
        }
          if ($city != '') {
            $temp .= ' AND it.city_fk ="' . $city . '"';
        }
         if ($unit_name != '') {
            $temp .= ' AND it.unit_fk ="' . $unit_name . '"';
        }
        if ($brand_name != '') {
            $temp .= ' AND it.brand_fk ="' . $brand_name . '"';
        }
        
                if ($type1 != '') {
            $temp .= ' AND it.type ="' . $type1 . '"';
        }
        
         if ($reg_id != '' || $city !='' || $unit_name != '' || $brand_name != '' || $type1!='') {
             
            $srchdata = array("name" => $reg_id,"city"=>$city ,"unit_name" => $unit_name, "brand_name" => $brand_name);
            $data['lab_name'] = $reg_id;
            $data['city_fk'] = $city;
            $data['unit_name'] = $unit_name;
            $data['brand_name'] = $brand_name;
            $data['type1'] = $type1;
            if($type ==1 || $type == 2 || $type ==8){
                 $totalRows=$this->Stationary_model->get_num_rows("Select it.* from inventory_item as it   where it.status='1' $temp ");
                $config["base_url"] = base_url() . "inventory/stationary_master/stationary_list?search=$reg_id&city=$city&unit_name=$unit_name&brand_name=$brand_name";

            $config["total_rows"] = $totalRows;
             $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
                     $data['query'] = $this->Stationary_model->get_val("Select it.*,tc.name,br.branch_name,ium.name as UnitName,ib.brand_name as BrandName from inventory_item as it LEFT JOIN test_cities as tc on tc.id = it.city_fk and tc.status='1' LEFT JOIN branch_master as br on br.id = it.branch_fk and br.status='1' LEFT JOIN inventory_unit_master as ium on ium.id = it.unit_fk and ium.status='1' LEFT JOIN inventory_brand as ib on ib.id = it.brand_fk and ib.status='1' where it.status='1' and it.category_fk='1' $temp order by it.id desc LIMIT $page,".$config['per_page']."");
            }else{
                $totalRows=$this->Stationary_model->get_num_rows("Select it.* from inventory_item as it   where it.status='1' $temp and it.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$login_id.")");
            $config["total_rows"] = $totalRows;
             $this->pagination->initialize($config);
             $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
             $data['query'] = $this->Stationary_model->get_val("Select it.*,tc.name,br.branch_name,ium.name as UnitName,ib.brand_name as BrandName from inventory_item as it LEFT JOIN test_cities as tc on tc.id = it.city_fk and tc.status='1' LEFT JOIN branch_master as br on br.id = it.branch_fk and br.status='1' LEFT JOIN inventory_unit_master as ium on ium.id = it.unit_fk and ium.status='1' LEFT JOIN inventory_brand as ib on ib.id = it.brand_fk and ib.status='1' where it.status='1' and it.category_fk='1' $temp and it.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = '".$login_id."') $temp order by it.id desc LIMIT $page,".$config['per_page']."");

            }
        } else {
            if($type ==1 || $type == 2 || $type ==8){  
               $totalRows=$this->Stationary_model->get_num_rows("Select it.* from inventory_item as it   where it.status='1' $temp ");
            
                $config["base_url"] = base_url() . "inventory/stationary_master/stationary_list?search=$reg_id&city=$city&unit_name=$unit_name&brand_name=$brand_name";

            $config["total_rows"] = $totalRows;
             $this->pagination->initialize($config);
            $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
          //  echo $db ="Select it.*,tc.name,br.branch_name,ium.name as UnitName,ib.brand_name as BrandName from inventory_item as it LEFT JOIN test_cities as tc on tc.id = it.city_fk and tc.status='1' LEFT JOIN branch_master as br on br.id = it.branch_fk and br.status='1' LEFT JOIN inventory_unit_master as ium on ium.id = it.unit_fk and ium.status='1' LEFT JOIN inventory_brand as ib on ib.id = it.brand_fk and ib.status='1' where it.status='1'  $temp order by it.id desc LIMIT $page,".$config['per_page']."";
         $data['query'] = $this->Stationary_model->get_val("Select it.*,tc.name,br.branch_name,ium.name as UnitName,ib.brand_name as BrandName from inventory_item as it LEFT JOIN test_cities as tc on tc.id = it.city_fk and tc.status='1' LEFT JOIN branch_master as br on br.id = it.branch_fk and br.status='1' LEFT JOIN inventory_unit_master as ium on ium.id = it.unit_fk and ium.status='1' LEFT JOIN inventory_brand as ib on ib.id = it.brand_fk and ib.status='1' where it.status='1' and it.category_fk='1'  $temp order by it.id desc LIMIT $page,".$config['per_page']."");

            }else{ 
                $totalRows=$this->Stationary_model->get_num_rows("Select it.* from inventory_item as it   where it.status='1'  and it.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = ".$login_id.")");
             $config["total_rows"] = $totalRows;
             $this->pagination->initialize($config);
             $page = ($this->input->get("per_page")) ? $this->input->get("per_page") : 0;
             $data['query'] = $this->Stationary_model->get_val("Select it.*,tc.name,br.branch_name,ium.name as UnitName,ib.brand_name as BrandName from inventory_item as it LEFT JOIN test_cities as tc on tc.id = it.city_fk and tc.status='1' LEFT JOIN branch_master as br on br.id = it.branch_fk and br.status='1' LEFT JOIN inventory_unit_master as ium on ium.id = it.unit_fk and ium.status='1' LEFT JOIN inventory_brand as ib on ib.id = it.brand_fk and ib.status='1' where it.status='1' and it.category_fk='1'  $temp and it.branch_fk in(SELECT `branch_fk`  FROM `user_branch`  WHERE STATUS = '1' AND user_fk = '".$login_id."') $temp order by it.id desc LIMIT $page,".$config['per_page']."");
            }
        }
        $data['unit_list'] = $this->Stationary_model->master_get_tbl_val('inventory_unit_master',array('status'=>'1'),array('name',"DESC"));
           $data['brand_list'] = $this->Stationary_model->master_get_tbl_val('inventory_brand',array('status'=>'1'),array('brand_name',"DESC"));
           $data['city_list'] = $this->Stationary_model->get_val("select * from test_cities where status='1' order by name asc");
            if($type =='1' || $type =='2' || $type =='5' ){
            $this->load->view('header');
        $this->load->view('nav', $data);
        }
        if($type =='8'){
            $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        }
        
        $this->load->view('inventory/stationary_list', $data);
        $this->load->view('footer');
    }

    public function add() {
//      

        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $type=$data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('city_fk', 'City Name', 'trim|required');
        $this->form_validation->set_rules('branch_fk', 'Branch Name', 'trim|required');
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|numeric|xss_clean|callback_check_minus');
		$this->form_validation->set_rules('box_price', 'Box Price', 'trim|xss_clean|required|callback_check_zero');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        if ($this->form_validation->run() != FALSE) {
            $data = array(
                'reagent_name' => $this->input->post('name'),
                 'city_fk' => $this->input->post('city_fk'),
                 'branch_fk' => $this->input->post('branch_fk'),
                "quantity" => $this->input->post('quantity'),
                "created_date" => date("Y-m-d H:i:s"),
                "created_by" => $data["login_data"]['id'],
                "category_fk" => "1",
                 "unit_fk"=>$this->input->post('unit_fk'),
                   "brand_fk"=>$this->input->post('brand_fk'),
                   "remark"=>$this->input->post('remark'),
                   'box_price'=>$this->input->post('box_price'),
                    'hsn_code'=>$this->input->post('hsn_code'),
					'location' => $this->input->post('location'),
                'type' => $this->input->post('type')
            );
         
            $data['query'] = $this->Stationary_model->master_get_insert("inventory_item", $data);

            $this->session->set_flashdata("success", array("Inventory Item  Successfull Added."));
            redirect("inventory/Stationary_master/stationary_list", "refresh");
        }
             $data['unit_list'] = $this->Stationary_model->master_get_tbl_val('inventory_unit_master',array('status'=>'1'),array('name',"DESC"));
           $data['brand_list'] = $this->Stationary_model->master_get_tbl_val('inventory_brand',array('status'=>'1'),array('brand_name',"DESC"));
           $data['city_list']  = $this->Stationary_model->get_val("select * from test_cities where status='1' order by name asc");
         
      if($type =='1' || $type =='2' || $type =='5' ){
            $this->load->view('header');
        $this->load->view('nav', $data);
        }
        if($type =='8'){
            $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        } 
        $this->load->view('inventory/stationary_add', $data);
           $this->load->view('footer');
    }

    public function stationary_edit() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
         $type=$data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data["eid"] = $this->uri->segment('4');
        $ids = $data["eid"];

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
         $this->form_validation->set_rules('city_fk', 'City Name', 'trim|required');
        $this->form_validation->set_rules('branch_fk', 'Branch Name', 'trim|required');
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|numeric|xss_clean|callback_check_minus');
		$this->form_validation->set_rules('box_price', 'Box Price', 'trim|xss_clean|required|callback_check_zero');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        $id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        if ($this->form_validation->run() != FALSE) {
            $post['reagent_name'] = $this->input->post('name');
            $post["quantity"] = $this->input->post('quantity');
            $post['city_fk'] = $this->input->post('city_fk');
            $post["branch_fk"] = $this->input->post('branch_fk');
            $post['status'] = 1;
            $post['modified_by'] = $data["login_data"]['id'];
            $post["category_fk"] = 1;
             $post["unit_fk"] = $this->input->post('unit_fk');
             $post["brand_fk"] = $this->input->post('brand_fk');
              $post["remark"] = $this->input->post('remark');
              $post["box_price"] = $this->input->post('box_price');
              $post['hsn_code'] = $this->input->post('hsn_code');
			  $post['location'] = $this->input->post('location');
                          $post['type'] = $this->input->post('type');
            $data['query'] = $this->Stationary_model->master_get_update("inventory_item", array('id' => $ids), $post);
            $this->session->set_flashdata("success", array("Inventory Successfull Updated."));
            redirect("inventory/Stationary_master/stationary_list", "refresh");
        } 
        
            $data['query'] = $this->Stationary_model->master_get_tbl_val("inventory_item", array("id" => $data["eid"]), array("id", "desc"));
              $sub_id = $data['query'][0]['city_fk'];
             $data['unit_list'] = $this->Stationary_model->master_get_tbl_val('inventory_unit_master',array('status'=>'1'),array('name',"DESC"));
           $data['brand_list'] = $this->Stationary_model->master_get_tbl_val('inventory_brand',array('status'=>'1'),array('brand_name',"DESC"));
           $data['city_list'] = $this->Stationary_model->get_val('select * from test_cities where status="1" order by name asc');
    $data['branch_list'] = $this->Stationary_model->get_val("select * from branch_master where status='1' and city = '".$sub_id."' order by branch_name ASC");  
        if($type =='1' || $type =='2' || $type =='5' ){
            $this->load->view('header');
        $this->load->view('nav', $data);
        }
        if($type =='8'){
            $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        } 
        $this->load->view('inventory/stationary_edit', $data);
            $this->load->view('footer');
    }

    public function stationary_delete() {
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $eid = $this->uri->segment('4');

        $data['query'] = $this->Stationary_model->master_get_update("inventory_item", array("id" => $eid), array("status" => "0"), array("id", "desc"));

        $this->session->set_flashdata("success", array("Inventory Item  Successfully Deleted."));
        redirect("inventory/Stationary_master/stationary_list", "refresh");
    }
public function check_minus() {
        $test_discount = $this->input->post('quantity');

        if ($test_discount < 0) {
             $this->form_validation->set_message('check_minus', "Nagative Digits Not allowed");

    return false;
        } else {
            return TRUE;
        }
    }
function check_zero() {
        $box_price = $this->input->post('box_price');

        if ($box_price != "") {
            if ($box_price <= 0) {
                $this->form_validation->set_message('check_zero', "Zero and negative digits are not allowed");
                return false;
            } else {
                return TRUE;
            }
        } else {
            $this->form_validation->set_message('check_zero', "Box Price is required");
            return false;
        }
    }	
}

?>