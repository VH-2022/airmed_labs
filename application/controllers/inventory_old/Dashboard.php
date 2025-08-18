<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Dashboard_model');
    }

    function index() {  
        $data["login_data"] = logindata();  
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]); 
            $data['branch_list'] = $this->Dashboard_model->get_val("select id,branch_name from branch_master where status='1'");
            $branch = $this->input->get_post('branch');
            $start_date = $this->input->get_post('start_date');
            $end_date = $this->input->get_post('end_date');
            if($branch ==''){
              $data['branch'] =2;
            }else{
               $data['branch'] = $branch;
            }
        $this->load->view('inventory/header');
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/dashboard', $data);
        $this->load->view('inventory/footer');
    }
function view(){
   if (!is_loggedin()) {
            redirect('login');
        }
        $data["login_data"] = logindata();
        $login_id = $data["login_data"]['id'];
        $type = $data["login_data"]['type'];
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $vid = $this->uri->segment(4);
        $data['query'] = $this->Dashboard_model->get_val("SELECT p.id,p.ponumber,p.`creteddate`,p.`discount`,p.`branchfk`,p.`poprice`,p.`indentcode`,p.`status`,p.remark,b.`branch_name`,v.vendor_name,a.`name` AS cretdby FROM inventory_pogenrate p LEFT JOIN `branch_master` b ON b.`id`=p.`branchfk` LEFT JOIN inventory_vendor v ON v.`id`=p.`vendorid` LEFT JOIN admin_master a ON a.`id`=p.`cretedby` WHERE p.`status` !='0' and p.id='".$vid."'");
//echo "<pre>";print_r($data['query']);die;
                $data['sub_data'] = $this->Dashboard_model->get_val("SELECT * from inventory_inward_master  WHERE status='1' and po_fk='".$vid."'");

                    $data['poitenm'] = $this->Dashboard_model->get_val("SELECT p.*,i.reagent_name,i.category_fk FROM inventory_poitem p LEFT JOIN inventory_item i ON i.`id`=p.`itemid` WHERE p.status='1' and p.poid='".$vid."'");
                    $data['bankall'] = $this->Dashboard_model->get_val("SELECT * from inventory_bank where status='1' ");
                    $data['banmpay'] = $this->Dashboard_model->get_val("SELECT p.id,p.banckid,p.neft,p.paydate,p.remark,b.bank_name from inventory_popayment p left join inventory_bank b on b.id=p.banckid where p.status='1' and p.poid='".$vid."'");
                    /* echo "<pre>";
                      print_r($data['poitenm']); die(); */
   $this->load->view("inventory/header",$data);
      $this->load->view("inventory/nav",$data);
      $this->load->view("inventory/view_polist",$data);
      $this->load->view("inventory/footer");
                 
}
function total_reagent() {
            $branch = $this->input->get_post('sub_id');
            $data['new_array']  =$this->Dashboard_model->getReagent($branch);
         
            $cnt = 1;
            $table = '';
            $table .= "<thead><th>No</th><th>Reagent Name</th><th>Price</th><th>Category Name</th><th>Unit Name</th></thead>";
            $table .= "<tbody>";
            if ($data['new_array'] != NULL) {
                foreach ($data['new_array'] as $test_val) {
                    if($test_val['quantity'] >$test_val['used']){ 
                        $total_price = ($test_val['quantity'] * $test_val['box_price']);
                    $table .= "<tr><td>" . $cnt . "</td><td>" . $test_val['reagent_name'] . "</td>";
                  
                    $table .="<td>" . $total_price ."</td>";
                    $table .= "<td>" . $test_val['Category'] . "</td>";
                    $table .= "<td>" . $test_val['Unit'] . "</td>";
                    $table .= "</tr>";
                    $cnt++;
                }
            }
            } else {
                $table .= "<tr><td>Record Not Found</td>";
            }
            $table .= "</tbody>";
            echo $table;
        }
    function total_test() {
            $id = $this->input->get_post('sub_id');
            $start_date = $this->input->get_post('start_date');
                $end_date = $this->input->get_post('end_date');
            $data['new_sub_array'] = $this->Dashboard_model->total_test($id,$start_date,$end_date);

            $cnt = 1;
            $table = '';
            $table .= "<thead><th>No</th><th>Test Name</th><th>Total</th></thead>";
            $table .= "<tbody>";
            if ($data['new_sub_array'] != NULL) {
                foreach ($data['new_sub_array'] as $test_val) {
                    if($test_val['Total'] !='0'){
                    $table .= "<tr><td>" . $cnt . "</td><td>" . $test_val['test_name'] . "</td>";
                  
                    $table .= "<td>" . $test_val['Total'] . "</td>";
                    $table .= "</tr>";
                    $cnt++;
                }
            }
            } else {
                $table .= "<tr><td>Record Not Found</td>";
            }
            $table .= "</tbody>";
            echo $table;
        }
        function total_expire() {
            $id = $this->input->get_post('sub_id');
           
            $data['expire_record'] = $this->Dashboard_model->lab_stationary($id);
            $cnt = 1;
            $table = '';
            $table .= "<thead><th>No</th><th>Item Name</th><th>Available Stock</th><th>Category Name</th><th>Unit Name</th><th>Expire Date</th></thead>";
            $table .= "<tbody>";
            if ($data['expire_record'] != NULL) {
                foreach ($data['expire_record'] as $sub_val) { 
                  $odate = explode("-",$sub_val['expire_date']);
                  $date12 = $odate[2].'-'.$odate[1].'-'.$odate[0];
                  if($sub_val['quantity'] > $sub_val['used']){
                    $table .= "<tr><td>" . $cnt . "</td><td>" . ucfirst($sub_val['reagent_name']) . "</td>";
                    $table .="<td>".$sub_val['Available']. "</td>";
                     $table .= "<td>" . ucfirst($sub_val['Category']) . "</td>";
                    $table .= "<td>" . ucfirst($sub_val['Unit']) . "</td>";
                    $table .= "<td>" . $date12 . "</td>";
                    $table .= "</tr>";
                    $cnt++;
                }
              }
            } else {
                $table .= "<tr><td>Record Not Found</td>";
            }
            $table .= "</tbody>";
            echo $table;
        }

        function getpodays(){
 $id = $this->input->get_post('sub_id');
            $start_date = $this->input->get_post('start_date');
           $end_date = $this->input->get_post('end_date');
     $data['query'] = $this->Dashboard_model->getpo_days($id,$start_date,$end_date);
                 $cnt = 1;
            $table = '';
            $table .= "<thead><th>No</th><th>PO Number</th><th>Vender Name</th><th>Date</th></thead>";
            $table .= "<tbody>";
            if ($data['query'] != NULL) {
                foreach ($data['query'] as $po_val) { 
                  $edate = explode(" ",$po_val['creteddate']);
                  $exp_date =explode("-",$edate[0]);

                  $date = $exp_date[2].'-'.$exp_date[1].'-'.$exp_date[0] .' '.$edate[1];
                    $table .= "<tr><td>" . $cnt . "</td><td><a href='view/".$po_val['id']."' target='_blank'>" . $po_val['ponumber'] . "</a></td>";
                 
                    $table .= "<td>" . $po_val['vendor_name'] . "</td>";
                     $table .= "<td>" . $date . "</td>";
                    $table .= "</tr>";
                    $cnt++;
                }
            } else {
                $table .= "<tr><td>Record Not Found</td>";
            }
            $table .= "</tbody>";
            echo $table;
        }
}
