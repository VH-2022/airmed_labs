<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Speciality_master extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('Speciality_model');
        $this->load->library('form_validation');
          $this->load->library('pagination');
    }

    function index(){

    if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];

        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");
        $id = $this->input->get_post('name');
        
        if($id !=''){
          
            $totalRows = $this->Speciality_model->intent_num($id);
           
           $data['intenet_id']=$id;
          
            $config = array();
            $config["base_url"] = base_url() . "Speciality_master/index?name=$id";
            $config["total_rows"] = $totalRows;
            $config["per_page"]=50;
            $config["uri_segment"] = 4;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
          
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            
            $data["query"] = $this->Speciality_model->intent_list($id,$config["per_page"], $page);
  
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }else{

             $srch = array();
         
             $totalRows = $this->Speciality_model->intent_num($id);
           
            $config = array();
            $config["base_url"] = base_url() . "Speciality_master/index";
            $config["total_rows"] = $totalRows;
            $config["per_page"]=50;
            $config["uri_segment"] = 3;
            $config['cur_tag_open'] = '<span>';
            $config['cur_tag_close'] = '</span>';
            $config['next_link'] = 'Next &rsaquo;';
            $config['prev_link'] = '&lsaquo; Previous';
          
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            
            $data["query"] = $this->Speciality_model->intent_list($id,$config["per_page"], $page);
   
            $data["links"] = $this->pagination->create_links();
            $data["counts"] = $page;
        }
       
        $data['test_list'] = $this->Speciality_model->get_val("select id,test_name from test_master where status='1'");
     
        $this->load->view('header',$data);
        $this->load->view('nav', $data);
        $this->load->view('speciality_list', $data);
        $this->load->view('footer');
    }
  
    function add(){
      

         $item_list = $this->input->post("item");      
        $name = $this->input->post("name");
            $data1 = array(
                'name'=>$name
            );
        $insert = $this->Speciality_model->master_fun_insert("specility_wise_test", $data1);

        $cnt = 0;
        if($insert){
        foreach ($item_list as $kkey) {
            $data = array(
                "spec_fk"=>$insert,
                "test_fk" => $kkey,
                
                "created_date" => date("Y-m-d H:i:s")
            );
           $this->Speciality_model->master_fun_insert("test_speciality", $data);
            $cnt++;
        }
     }
        $this->session->set_flashdata("success", array("Speciality successfully added."));
        redirect("Speciality_master/index","refresh");
    }
 

 function edit() {

     if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];

        $tid = $this->uri->segment(3);


$edit_item_details = $this->Speciality_model->get_val("SELECT sp.* , sub.*,t.`test_name`  FROM specility_wise_test as sp LEFT JOIN test_speciality as sub ON `sp`.`id`=`sub`.`spec_fk` LEFT join test_master as t on sub.test_fk = t.id WHERE  sp.status = '1' and sp.id = '".$tid."' ");

$name = $edit_item_details[0]['name'];

 $selected_item = array();
        $selected_quentity = array();
        $selected_item_name = array();
        foreach ($edit_item_details as $key) { 
            $selected_item[] = $key["test_fk"];
           
        }
        
       $test_list = $this->Speciality_model->get_val("select id,test_name from test_master where status='1'");
        ?>
        
        <div class="form-group">
        <input type="hidden" name="id" value="<?php echo $tid;?>">
            
            <label for="message-text" class="form-control-label">Name:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $name;?>">
        </div>
        <div class="form-group">
            <label for="message-text" class="form-control-label">Add Test:</label>
            <select class="chosen chosen-select" name="item" id="selected_item_edit" onchange="select_item_edit();">
                <option value="">--Select--</option>
                <?php
                foreach ($test_list as $mkey) {
                    if (!in_array($mkey["id"], $selected_item)) {
                        ?>
                        <option value="<?= $mkey["id"] ?>"><?= $mkey["test_name"] ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
      
        <table class="table">
            <thead>
                <tr>
                    <th>Test Name</th>
                   
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="selected_items_edit">
                <?php
                $cnt = 0;
                foreach ($edit_item_details as $ikey) {
                    ?>
                    <tr id="tr_edit_<?= $cnt; ?>">
                        <td><?= $ikey["test_name"] ?></td>
                         
                        <td><input type="hidden" name="item[]" value="<?= $ikey["test_fk"] ?>"></td>
                        <td><a href="javascript:void(0);" onclick="delete_city_price_edit('<?= $cnt; ?>', '<?= $ikey["test"] ?>', '<?= $ikey["test_fk"] ?>')"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php
                    $cnt++;
                }
                ?>
            <script>$city_cnt_edit = <?= $cnt ?>;</script>
        </tbody>
        </table>
        <?php
    }

    function update(){

         $this->Speciality_model->new_fun_update("specility_wise_test", array("id" => $this->input->post("id")), array("status" => "0"));
          $this->Speciality_model->new_fun_update("test_speciality", array("spec_fk" => $this->input->post("id")), array("status" => "0"));
   
       $item_list = $this->input->post("item");      
        $name = $this->input->post("name");
            $data1 = array(
                'name'=>$name
            );
        $insert = $this->Speciality_model->master_fun_insert("specility_wise_test", $data1);

        $cnt = 0;
        if($insert){
        foreach ($item_list as $kkey) {
            $data = array(
                "spec_fk"=>$insert,
                "test_fk" => $kkey,
                
                "created_date" => date("Y-m-d H:i:s")
            );
           $this->Speciality_model->master_fun_insert("test_speciality", $data);
            $cnt++;
        }
     }
        $this->session->set_flashdata("success", array("Speciality successfully added."));
        redirect("Speciality_master/index","refresh");
    }

    function delete(){
        if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $id = $data["login_data"]['id'];
        $tid = $this->uri->segment('3');

        $data['query'] = $this->Speciality_model->master_fun_update("specility_wise_test", $tid, array("status" => "0"));

       $this->session->set_flashdata("success", array("Speciality successfull deleted."));
       redirect("Speciality_master/index", "refresh");
    }
}
