<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock_issue_report_daily extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('inventory/Stock_issue_report_model');

        $this->load->library('email');
        $this->load->helper('string');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        ini_set('display_errors', 1);
    }

    function index() {

        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();

        $start_date = $this->input->get("start_date");
        $end_date = $this->input->get("start_date");
        $branch = $this->input->get("branch");
        $item = $this->input->get("item");

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['branch'] = $branch;
        $data['item'] = $item;

         $new_array = array();
              $reagentsList= $this->user_model->get_val("SELECT `inventory_item`.*,
                `inventory_category`.`name` AS category 
                FROM `inventory_item` 
                INNER JOIN `inventory_category` ON `inventory_item`.`category_fk`=`inventory_category`.`id` 
                WHERE `inventory_category`.`status`='1' 
                AND `inventory_item`.`status`='1' 
                AND branch_fk='$branch' AND `inventory_item`.`category_fk` IN (1,2,3) 
                ORDER BY `inventory_item`.`reagent_name` ASC");
              
              
           $reagentsList=   $this->get_item_array($branch);
           
    foreach($reagentsList as $items){
        $item=$items['id'];

         $start_date = $this->input->get("start_date");
        $end_date = $this->input->get("start_date");
        if ($start_date != "" && $end_date != "" && $branch != "" ) {
            /* ITEM BASIC INFO START */
            $temp = " AND i.reaqgentfk = '$item' AND i.branchfk = '$branch'";

            /* $q1 = "SELECT i.id,b.`branch_name`,r.reagent_name,s.`batch_no`,ib.brand_name as BrandName,
              i.`quantity`,r.per_pack,s.used,i.`creteddate`,r.test_quantity,s.expire_date,
              IF(r.category_fk = 3,r.test_quantity,r.quantity) as packet_quantity
              FROM `inventory_usedreagent` i
              LEFT JOIN `branch_master` b ON b.`id`=i.`branchfk`
              LEFT JOIN `inventory_item` r ON r.`id`=i.`reaqgentfk`
              LEFT JOIN `inventory_stock_master` s ON s.`id`=i.`indedfk`
              LEFT JOIN inventory_brand as ib on r.brand_fk = ib.id and ib.status='1'
              WHERE i.status='1'  $temp
              order by i.creteddate asc";
             */
            //$data["query"] = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' GROUP BY s.id ORDER BY s.id ASC");
            $data["query"] = $this->Stock_issue_report_model->get_val("SELECT b.`branch_name`,s.`id`,s.item_fk,r.`reagent_name`,r.location,r.test_quantity,r.`category_fk`,SUM(s.quantity) as totalqty,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item'  GROUP BY s.item_fk ORDER BY s.id ASC");

            //$data['query'] = $this->Stock_issue_report_model->get_val($q1);
            /* END */

            //$old_opning_stock = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1 GROUP BY s.id");
            //echo "SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1";die();
            $old_opning_stock = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1");
            if (!empty($old_opning_stock)) {
                $cd = explode(" ", $old_opning_stock[0]["created_date"]);
                if (!empty($cd)) {
                    $new_start_date = $cd[0];
                } else {
                    $new_start_date = $start_date;
                }
            } else {
                $od_opning = 0;
                $new_start_date = $start_date;
            }
//echo $new_start_date."---".$end_date;die();
//echo "<pre>";print_r($old_opning_stock);die();

            $end_date1 = date('Y-m-d', strtotime($end_date . ' +1 day'));
            $period = new DatePeriod(
                    new DateTime($new_start_date), new DateInterval('P1D'), new DateTime($end_date1)
            );
            $dates = array();
            foreach ($period as $key => $value) {
                $dates[] = $value->format('Y-m-d');
            }



            /* GET Pack wise reagent STart */
            //$reagent_pack = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_usedreagent` WHERE `status`='1' AND `branchfk`='" . $branch . "' AND reaqgentfk='" . $item . "'");

            $reagent_pack = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,r.test_quantity,s.`quantity`,r.location,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' ORDER BY s.id ASC");
            //echo "<pre>"; print_r($reagent_pack);die();  
            $sdate = "1999-01-01";
            $cnt = 0;
            $alength = count($reagent_pack);
            foreach ($reagent_pack as $rpkey) {
                if ($alength > $cnt + 1) {
                    $edate = $reagent_pack[$cnt + 1]["created_date"];
                } else {
                    $edate = date("Y-m-d H:i:s");
                }
                $reagent_test = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_jobstock` WHERE `status`='1' AND branchid='" . $branch . "' AND `itemfk`='" . $item . "' and creteddate>='" . $sdate . "' and creteddate<='" . $edate . "' order by creteddate");

                $reagent_pack1 = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_usedreagent` WHERE `status`='1' AND `branchfk`='" . $branch . "' AND reaqgentfk='" . $item . "' and creteddate>='" . $sdate . "' and creteddate<='" . $edate . "' order by creteddate");
                //$reagent_test = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND created_date>='" . $sdate . "' and created_date<='" . $edate . "'");


                $sdate = $rpkey["created_date"];
                $reagent_pack[$cnt]["ur"] = $reagent_pack1[0]["id"];
                $reagent_pack[$cnt]["perform_test"] = $reagent_test;
                $cnt++;
            }
            /* END */

            //echo "<pre>";
            //print_r($reagent_pack);
            //die();

           
            $cnt = 0;
            $cnt1 = 0;
            $old_opning = 0;


            /*
              foreach ($dates as $key) {
              $open_stock = $old_opning;
              $closing_stock = $old_opning;
              $issue = 0;
              $received = 0;
              $new_bottol_time = "";
              $new_bottol_test = 0;
              foreach ($reagent_pack as $dkey) {
              if ($key . " 00:00:00" <= $dkey["created_date"] && $key . " 23:59:59" >= $dkey["created_date"]) {
              //die("OK");
              $received = $data['query'][0]["test_quantity"] * $dkey["quantity"];
              //echo $received; die();
              $old_opning = $old_opning + $dkey["quantity"];
              $closing_stock = $old_opning + $received;
              $new_bottol_time = $dkey["created_date"];
              }
              $ccnt = 0;
              foreach ($dkey["perform_test"] as $ptsKey) {//echo "<pre>"; print_r($dkey);die();
              if ($key . " 00:00:00" <= $ptsKey["creteddate"] && $key . " 23:59:59" >= $ptsKey["creteddate"]) {
              $issue = $issue + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
              if (!empty($new_bottol_time) && $new_bottol_time <= $ptsKey["creteddate"]) {
              $new_bottol_test = $new_bottol_test + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
              if ($ccnt == 0) {
              $closing_stock = $received;
              }
              }
              //if (empty($new_bottol_time)) {
              $closing_stock = $closing_stock - ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
              //} else {
              //$closing_stock = $closing_stock - $new_bottol_test;
              //}
              }
              }
              }
              if ($key . " 00:00:00" >= $start_date) {
              $new_array[] = array("srno" => $cnt + 1, "date" => $key, "compony" => $data['query'][0]["BrandName"], "pack_size" => $data['query'][0]["test_quantity"], "lot" => $data['query'][0]["batch_no"], "expire_date" => $data['query'][0]["expire_date"], "opening" => $open_stock, "received" => $received, "issue" => $issue, "new_bottol_test" => $new_bottol_test, "closing" => $closing_stock);
              }
              //if ($issue > 0) {
              $old_opning = $closing_stock;
              //}
              }
             */


            $use_ragent1 = 0;
            $use_reagent_cnt = 0;
            $udm = 0;
            $NewStock = 0;
            $westage = 0;
            foreach ($dates as $key) {
                $open_stock = $old_opning;
                $closing_stock = $old_opning;
                $issue = 0;
                $received = 0;
                $new_bottol_time = "";
                $new_bottol_test = 0;

                $myregent = $this->Stock_issue_report_model->get_val("SELECT inventory_usedreagent.*,`inventory_item`.`reagent_name`,`inventory_item`.`test_quantity` FROM `inventory_usedreagent` LEFT JOIN `inventory_item` ON `inventory_item`.`id`=inventory_usedreagent.`reaqgentfk` WHERE inventory_usedreagent.`status`='1' AND inventory_usedreagent.`branchfk`='" . $branch . "' AND inventory_usedreagent.reaqgentfk='" . $item . "' AND inventory_usedreagent.creteddate LIKE '%" . $key . "%' ORDER BY inventory_usedreagent.creteddate");

                if (count($myregent) > 0) {
                    $wastage = $NewStock;
                    $NewStock = $myregent[0]["test_quantity"];
                }
                foreach ($reagent_pack as $dkey) {

                    if ($key . " 00:00:00" <= $dkey["created_date"] && $key . " 23:59:59" >= $dkey["created_date"]) {
                        $received = $dkey["test_quantity"] * $dkey["quantity"];
                        $old_opning = $old_opning + $dkey["quantity"];
                        $closing_stock = $received;
                        $new_bottol_time = $dkey["created_date"];
                    }
                    $ccnt = 0;

                    foreach ($dkey["perform_test"] as $ptsKey) {
                        //echo $ptsKey["usedreagent_fk"]."---".$use_reagent_cnt."<br>";
                        //echo "<br>";
                        //echo $NewStock . '-' . $westage;
                        if ($use_ragent1 != $ptsKey["usedreagent_fk"]) {
                            $use_ragent1 = $ptsKey["usedreagent_fk"];
                            $udm = $use_reagent_cnt;
                            $use_reagent_cnt = 0;
                            //echo "OK<br>";
                            //                      $westage = $NewStock;
                            //              print_r($ptsKey);
                            if (date('Y-m-d', strtotime($key)) == date('Y-m-d', strtotime($ptsKey["creteddate"]))) {
                                //                        $wastage= $westage;
                                //                    echo   $key;
                                //                  die("test");
                                //                    
                            }
                            // $NewStock = $dkey["test_quantity"];
                        }

                        if ($key . " 00:00:00" <= $ptsKey["creteddate"] && $key . " 23:59:59" >= $ptsKey["creteddate"]) {
                            $issue = $issue + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                            if (!empty($new_bottol_time) && $new_bottol_time <= $ptsKey["creteddate"]) {
                                $new_bottol_test = $new_bottol_test + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                                if ($ccnt == 0) {
                                    //$closing_stock = $received;
                                }
                            }
                            if (empty($new_bottol_time)) {
                                $closing_stock = $open_stock + $received - ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                            } else {
                                $closing_stock = $open_stock + $received - $new_bottol_test;
                            }
                        }
                        $closing_stock = $open_stock + $received - $issue;
                        //echo $use_reagent_cnt."----".$cnt1;die();
                        if ($use_ragent1 != $ptsKey["usedreagent_fk"]) {
                            $use_reagent_cnt = $use_reagent_cnt + $issue;
                        }
                        if ($use_reagent_cnt == 0 && $cnt1 != 0) {
                            $rred = $dkey["quantity"] - $udm;
                            //$closing_stock = $closing_stock - $rred;
                        }
                        // echo "<br/>".$NewStock."- ". $issue;
                        // $NewStock = $NewStock - $issue;

                        $cnt1++;
                    }
                }
                //echo $use_reagent_cnt . "--" . $issue . "---" . $closing_stock . "---" . $received;
                //die();
                if ($key . " 00:00:00" >= $start_date) {
                    $NewStock = $NewStock - $issue;
                    $new_array[] = array("wastage" => $wastage,
                        "srno" => $cnt + 1,
                        "item"=>$items['reagent_name'],
                        "date" => $key,
                        "compony" => $data['query'][0]["BrandName"],
                        "pack_size" => $data['query'][0]["test_quantity"],
                        "lot" => $data['query'][0]["batch_no"],
                        "expire_date" => $data['query'][0]["expire_date"],
                        "opening" => $open_stock,
                        "received" => $received,
                        "issue" => $issue,
                        "new_bottol_test" => $new_bottol_test,
                        "closing" => $closing_stock - $wastage,
                        "westage" => $udm);
                }
                $wastage = 0;
                //if ($issue > 0) {
                $old_opning = $closing_stock;
                //}
            }
    }



            //echo "<pre>";
            //print_r($new_array);
            //die();

            $data["new_array"] = $new_array;
        }

        $data['branch_list'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => '1'), array("id", "asc"));
        if ($branch != "") {
            $data['item_list'] = $this->user_model->master_fun_get_tbl_val("inventory_item", array('status' => '1'), array("reagent_name", "asc"));
        }
        $this->load->view('header', $data);
        $this->load->view('inventory/nav', $data);
        $this->load->view('inventory/stock_issue_report_daily', $data);
        $this->load->view('footer');
    }

//    public function get_item() {
//        $postData = $this->input->post();
//        $id = $postData['id'];
//        $result = $this->Stock_issue_report_model->get_val("select id,reagent_name 
//                    from inventory_item 
//                    WHERE status = '1' AND branch_fk = '$id' ORDER BY reagent_name ASC");
//
//        print_r(json_encode($result));
//        exit;
//    }
    
    
        function get_item() {
        $id = $this->input->post('id');
        $query = $this->Stock_issue_report_model->get_val("SELECT `inventory_item`.*,
                `inventory_category`.`name` AS category 
                FROM `inventory_item` 
                INNER JOIN `inventory_category` ON `inventory_item`.`category_fk`=`inventory_category`.`id` 
                WHERE `inventory_category`.`status`='1' 
                AND `inventory_item`.`status`='1' 
                AND branch_fk='$id' AND `inventory_item`.`category_fk` IN (1,2) 
                ORDER BY `inventory_item`.`reagent_name` ASC");
        $lab_stats = '';
        $lab_stats .= '<option value="">Select Item</option>';
        if (!empty($query)) {
            $dup = array();
            foreach ($query as $key => $val) {

                if (!in_array($val['id'], $dup)) {
                    $lab_stats .= '<option value="' . $val['id'] . '">' . $val['reagent_name'] . ' ( ' . $val["category"] . ' )</option>';
                }
                $dup[] = $val['id'];
            }

            $query1 = $this->Stock_issue_report_model->get_val("SELECT `inventory_item`.* FROM `inventory_item` 
                    INNER JOIN `inventory_machine_branch` ON `inventory_machine_branch`.`machine_fk`=`inventory_item`.`machine` 
                    INNER JOIN `inventory_machine` ON `inventory_machine`.`id`=inventory_machine_branch.`machine_fk` 
                    WHERE `inventory_item`.`status`='1' 
                    AND `inventory_item`.`category_fk`='3' 
                    AND `inventory_machine`.`status`='1' 
                    AND `inventory_machine_branch`.`status`='1' 
                    AND `inventory_machine_branch`.`branch_fk`='" . $id . "'");
            foreach ($query1 as $key => $val) {

                if (!in_array($val['id'], $dup)) {
                    $lab_stats .= '<option value="'
                            . $val['id'] . '">' . $val['reagent_name'] . ' ( Reagent )</option>';
                }
                $dup[] = $val['id'];
            }

            echo $lab_stats;
        }
        exit;
    }


   function get_item_array($id) {
       $op=array();

        $query = $this->Stock_issue_report_model->get_val("SELECT `inventory_item`.*,
                `inventory_category`.`name` AS category 
                FROM `inventory_item` 
                INNER JOIN `inventory_category` ON `inventory_item`.`category_fk`=`inventory_category`.`id` 
                WHERE `inventory_category`.`status`='1' 
                AND `inventory_item`.`status`='1' 
                AND branch_fk='$id' AND  `inventory_item`.`category_fk` IN (1,2) 
                ORDER BY `inventory_item`.`reagent_name` ASC");
        $lab_stats = '';
        $lab_stats .= '<option value="">Select Item</option>';
        if (!empty($query)) {
            $dup = array();
            foreach ($query as $key => $val) {

                if (!in_array($val['id'], $dup)) {
                 ///   $op[]=$val;
                    $lab_stats .= '<option value="' . $val['id'] . '">' . $val['reagent_name'] . ' ( ' . $val["category"] . ' )</option>';
                }
                $dup[] = $val['id'];
            }

            $query1 = $this->Stock_issue_report_model->get_val("SELECT `inventory_item`.* FROM `inventory_item` 
                    INNER JOIN `inventory_machine_branch` ON `inventory_machine_branch`.`machine_fk`=`inventory_item`.`machine` 
                    INNER JOIN `inventory_machine` ON `inventory_machine`.`id`=inventory_machine_branch.`machine_fk` 
                    WHERE `inventory_item`.`status`='1' 
                    AND `inventory_item`.`category_fk`='3' 
                    AND `inventory_machine`.`status`='1' 
                    AND `inventory_machine_branch`.`status`='1' 
                    AND `inventory_machine_branch`.`branch_fk`='" . $id . "' order by reagent_name");
            foreach ($query1 as $key => $val) {

                if (!in_array($val['id'], $dup)) {
                     $op[]=$val;
                    $lab_stats .= '<option value="'
                            . $val['id'] . '">' . $val['reagent_name'] . ' ( Reagent )</option>';
                }
                $dup[] = $val['id'];
            }

           // echo $lab_stats;
        }
        return  $op;
        exit;
    }


    public function get_pdf() {

        $start_date = $this->input->get("start_date");
        $end_date = $this->input->get("start_date");
        $branch = $this->input->get("branch");
        $item = $this->input->get("item");

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['branch'] = $branch;
        $data['item'] = $item;
        $data['branch_name'] = $this->user_model->get_val("select branch_name from branch_master where status='1' AND id='$branch'");
        //print_r($data['branch_name']); exit;
        $new_array = array();
        $reagentsList = $this->user_model->get_val("SELECT `inventory_item`.*,
                `inventory_category`.`name` AS category 
                FROM `inventory_item` 
                INNER JOIN `inventory_category` ON `inventory_item`.`category_fk`=`inventory_category`.`id` 
                WHERE `inventory_category`.`status`='1' 
                AND `inventory_item`.`status`='1' 
                AND branch_fk='$branch' AND `inventory_item`.`category_fk` IN (1,2,3) 
                ORDER BY `inventory_item`.`reagent_name` ASC");


        $reagentsList = $this->get_item_array($branch);

        foreach ($reagentsList as $items) {
            $item = $items['id'];

            $start_date = $this->input->get("start_date");
            $end_date = $this->input->get("start_date");
            if ($start_date != "" && $end_date != "" && $branch != "") {
                /* ITEM BASIC INFO START */
                $temp = " AND i.reaqgentfk = '$item' AND i.branchfk = '$branch'";

                /* $q1 = "SELECT i.id,b.`branch_name`,r.reagent_name,s.`batch_no`,ib.brand_name as BrandName,
                  i.`quantity`,r.per_pack,s.used,i.`creteddate`,r.test_quantity,s.expire_date,
                  IF(r.category_fk = 3,r.test_quantity,r.quantity) as packet_quantity
                  FROM `inventory_usedreagent` i
                  LEFT JOIN `branch_master` b ON b.`id`=i.`branchfk`
                  LEFT JOIN `inventory_item` r ON r.`id`=i.`reaqgentfk`
                  LEFT JOIN `inventory_stock_master` s ON s.`id`=i.`indedfk`
                  LEFT JOIN inventory_brand as ib on r.brand_fk = ib.id and ib.status='1'
                  WHERE i.status='1'  $temp
                  order by i.creteddate asc";
                 */
                //$data["query"] = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' GROUP BY s.id ORDER BY s.id ASC");
                $data["query"] = $this->Stock_issue_report_model->get_val("SELECT b.`branch_name`,s.`id`,s.item_fk,r.`reagent_name`,r.location,r.test_quantity,r.`category_fk`,SUM(s.quantity) as totalqty,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item'  GROUP BY s.item_fk ORDER BY s.id ASC");

                //$data['query'] = $this->Stock_issue_report_model->get_val($q1);
                /* END */

                //$old_opning_stock = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1 GROUP BY s.id");
                //echo "SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1";die();
                $old_opning_stock = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1");
                if (!empty($old_opning_stock)) {
                    $cd = explode(" ", $old_opning_stock[0]["created_date"]);
                    if (!empty($cd)) {
                        $new_start_date = $cd[0];
                    } else {
                        $new_start_date = $start_date;
                    }
                } else {
                    $od_opning = 0;
                    $new_start_date = $start_date;
                }
//echo $new_start_date."---".$end_date;die();
//echo "<pre>";print_r($old_opning_stock);die();

                $end_date1 = date('Y-m-d', strtotime($end_date . ' +1 day'));
                $period = new DatePeriod(
                        new DateTime($new_start_date), new DateInterval('P1D'), new DateTime($end_date1)
                );
                $dates = array();
                foreach ($period as $key => $value) {
                    $dates[] = $value->format('Y-m-d');
                }



                /* GET Pack wise reagent STart */
                //$reagent_pack = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_usedreagent` WHERE `status`='1' AND `branchfk`='" . $branch . "' AND reaqgentfk='" . $item . "'");

                $reagent_pack = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,r.test_quantity,s.`quantity`,r.location,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' ORDER BY s.id ASC");
                //echo "<pre>"; print_r($reagent_pack);die();  
                $sdate = "1999-01-01";
                $cnt = 0;
                $alength = count($reagent_pack);
                foreach ($reagent_pack as $rpkey) {
                    if ($alength > $cnt + 1) {
                        $edate = $reagent_pack[$cnt + 1]["created_date"];
                    } else {
                        $edate = date("Y-m-d H:i:s");
                    }
                    $reagent_test = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_jobstock` WHERE `status`='1' AND branchid='" . $branch . "' AND `itemfk`='" . $item . "' and creteddate>='" . $sdate . "' and creteddate<='" . $edate . "' order by creteddate");

                    $reagent_pack1 = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_usedreagent` WHERE `status`='1' AND `branchfk`='" . $branch . "' AND reaqgentfk='" . $item . "' and creteddate>='" . $sdate . "' and creteddate<='" . $edate . "' order by creteddate");
                    //$reagent_test = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND created_date>='" . $sdate . "' and created_date<='" . $edate . "'");


                    $sdate = $rpkey["created_date"];
                    $reagent_pack[$cnt]["ur"] = $reagent_pack1[0]["id"];
                    $reagent_pack[$cnt]["perform_test"] = $reagent_test;
                    $cnt++;
                }
                /* END */

                //echo "<pre>";
                //print_r($reagent_pack);
                //die();


                $cnt = 0;
                $cnt1 = 0;
                $old_opning = 0;


                /*
                  foreach ($dates as $key) {
                  $open_stock = $old_opning;
                  $closing_stock = $old_opning;
                  $issue = 0;
                  $received = 0;
                  $new_bottol_time = "";
                  $new_bottol_test = 0;
                  foreach ($reagent_pack as $dkey) {
                  if ($key . " 00:00:00" <= $dkey["created_date"] && $key . " 23:59:59" >= $dkey["created_date"]) {
                  //die("OK");
                  $received = $data['query'][0]["test_quantity"] * $dkey["quantity"];
                  //echo $received; die();
                  $old_opning = $old_opning + $dkey["quantity"];
                  $closing_stock = $old_opning + $received;
                  $new_bottol_time = $dkey["created_date"];
                  }
                  $ccnt = 0;
                  foreach ($dkey["perform_test"] as $ptsKey) {//echo "<pre>"; print_r($dkey);die();
                  if ($key . " 00:00:00" <= $ptsKey["creteddate"] && $key . " 23:59:59" >= $ptsKey["creteddate"]) {
                  $issue = $issue + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                  if (!empty($new_bottol_time) && $new_bottol_time <= $ptsKey["creteddate"]) {
                  $new_bottol_test = $new_bottol_test + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                  if ($ccnt == 0) {
                  $closing_stock = $received;
                  }
                  }
                  //if (empty($new_bottol_time)) {
                  $closing_stock = $closing_stock - ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                  //} else {
                  //$closing_stock = $closing_stock - $new_bottol_test;
                  //}
                  }
                  }
                  }
                  if ($key . " 00:00:00" >= $start_date) {
                  $new_array[] = array("srno" => $cnt + 1, "date" => $key, "compony" => $data['query'][0]["BrandName"], "pack_size" => $data['query'][0]["test_quantity"], "lot" => $data['query'][0]["batch_no"], "expire_date" => $data['query'][0]["expire_date"], "opening" => $open_stock, "received" => $received, "issue" => $issue, "new_bottol_test" => $new_bottol_test, "closing" => $closing_stock);
                  }
                  //if ($issue > 0) {
                  $old_opning = $closing_stock;
                  //}
                  }
                 */


                $use_ragent1 = 0;
                $use_reagent_cnt = 0;
                $udm = 0;
                $NewStock = 0;
                $westage = 0;
                foreach ($dates as $key) {
                    $open_stock = $old_opning;
                    $closing_stock = $old_opning;
                    $issue = 0;
                    $received = 0;
                    $new_bottol_time = "";
                    $new_bottol_test = 0;

                    $myregent = $this->Stock_issue_report_model->get_val("SELECT inventory_usedreagent.*,`inventory_item`.`reagent_name`,`inventory_item`.`test_quantity` FROM `inventory_usedreagent` LEFT JOIN `inventory_item` ON `inventory_item`.`id`=inventory_usedreagent.`reaqgentfk` WHERE inventory_usedreagent.`status`='1' AND inventory_usedreagent.`branchfk`='" . $branch . "' AND inventory_usedreagent.reaqgentfk='" . $item . "' AND inventory_usedreagent.creteddate LIKE '%" . $key . "%' ORDER BY inventory_usedreagent.creteddate");

                    if (count($myregent) > 0) {
                        $wastage = $NewStock;
                        $NewStock = $myregent[0]["test_quantity"];
                    }
                    foreach ($reagent_pack as $dkey) {

                        if ($key . " 00:00:00" <= $dkey["created_date"] && $key . " 23:59:59" >= $dkey["created_date"]) {
                            $received = $dkey["test_quantity"] * $dkey["quantity"];
                            $old_opning = $old_opning + $dkey["quantity"];
                            $closing_stock = $received;
                            $new_bottol_time = $dkey["created_date"];
                        }
                        $ccnt = 0;

                        foreach ($dkey["perform_test"] as $ptsKey) {
                            //echo $ptsKey["usedreagent_fk"]."---".$use_reagent_cnt."<br>";
                            //echo "<br>";
                            //echo $NewStock . '-' . $westage;
                            if ($use_ragent1 != $ptsKey["usedreagent_fk"]) {
                                $use_ragent1 = $ptsKey["usedreagent_fk"];
                                $udm = $use_reagent_cnt;
                                $use_reagent_cnt = 0;
                                //echo "OK<br>";
                                //                      $westage = $NewStock;
                                //              print_r($ptsKey);
                                if (date('Y-m-d', strtotime($key)) == date('Y-m-d', strtotime($ptsKey["creteddate"]))) {
                                    //                        $wastage= $westage;
                                    //                    echo   $key;
                                    //                  die("test");
                                    //                    
                                }
                                // $NewStock = $dkey["test_quantity"];
                            }

                            if ($key . " 00:00:00" <= $ptsKey["creteddate"] && $key . " 23:59:59" >= $ptsKey["creteddate"]) {
                                $issue = $issue + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                                if (!empty($new_bottol_time) && $new_bottol_time <= $ptsKey["creteddate"]) {
                                    $new_bottol_test = $new_bottol_test + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                                    if ($ccnt == 0) {
                                        //$closing_stock = $received;
                                    }
                                }
                                if (empty($new_bottol_time)) {
                                    $closing_stock = $open_stock + $received - ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
                                } else {
                                    $closing_stock = $open_stock + $received - $new_bottol_test;
                                }
                            }
                            $closing_stock = $open_stock + $received - $issue;
                            //echo $use_reagent_cnt."----".$cnt1;die();
                            if ($use_ragent1 != $ptsKey["usedreagent_fk"]) {
                                $use_reagent_cnt = $use_reagent_cnt + $issue;
                            }
                            if ($use_reagent_cnt == 0 && $cnt1 != 0) {
                                $rred = $dkey["quantity"] - $udm;
                                //$closing_stock = $closing_stock - $rred;
                            }
                            // echo "<br/>".$NewStock."- ". $issue;
                            // $NewStock = $NewStock - $issue;

                            $cnt1++;
                        }
                    }
                    //echo $use_reagent_cnt . "--" . $issue . "---" . $closing_stock . "---" . $received;
                    //die();
                    if ($key . " 00:00:00" >= $start_date) {
                        $NewStock = $NewStock - $issue;
                        $new_array[] = array("wastage" => $wastage,
                            "srno" => $cnt + 1,
                            "item" => $items['reagent_name'],
                            "date" => $key,
                            "compony" => $data['query'][0]["BrandName"],
                            "pack_size" => $data['query'][0]["test_quantity"],
                            "lot" => $data['query'][0]["batch_no"],
                            "expire_date" => $data['query'][0]["expire_date"],
                            "opening" => $open_stock,
                            "received" => $received,
                            "issue" => $issue,
                            "new_bottol_test" => $new_bottol_test,
                            "closing" => $closing_stock - $wastage,
                            "westage" => $udm);
                    }
                    $wastage = 0;
                    //if ($issue > 0) {
                    $old_opning = $closing_stock;
                    //}
                }
            }



//            echo "<pre>";
//            print_r($new_array);
//            die();

            $data["new_array"] = $new_array;
        }
        
        $date = date("_Y-m-d_H:i:s");
        $pdfFilePath = FCPATH . "/upload/employee/daily_stock_issue_report" . $date . ".pdf";

        ini_set('memory_limit', '128M');

        $html = $this->load->view('inventory/daily_stock_issue_report_pdf', $data, true); 

        if (file_exists($pdfFilePath)) {
            $this->delete_downloadfile($pdfFilePath);
        }

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->autoScriptToLang = true;
        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
        $pdf->autoVietnamese = true;
        $pdf->autoArabic = true;
        $pdf->autoLangToFont = true;

        /* $pdf->SetHTMLHeader('<body>
          <div class="pdf_container">
          <div class="main_set_pdng_div">
          <div class="brdr_full_div">
          <div class="header_full_div">
          <img class="set_logo" src="logo.png" style="margin-top:15px;"/>
          </div>'); */

        $pdf->AddPage('p', // L - landscape, P - portrait
                '', '', '', '', 5, // margin_left
                5, // margin right
                5, // margin top
                5, // margin bottom
                2, // margin header
                2); // margin footer


        /* $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
          <p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
          <p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
          </div>
          <p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
          <p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
          <p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
          </body>
          </html>'); */

        $data["total_page"] = count($pdf->pages);
        //$data["page"] = $pdf->getPageCount();
        $pdf->WriteHTML($html);

        $pdf->Output("Reagent-Stock-Report-" . date("d-m-Y H:i") . ".pdf", 'D'); // save to file because we can

        $downld = $this->_push_file($pdfFilePath, "stock_issue_report" . $date . ".pdf");
        $this->session->set_flashdata("success", "Daily Stock issue report has downloaded successfully.");
        redirect($pdfFilePath);
        
        
    }

    
    
//    public function get_pdf() {
//
//        $start_date = $this->input->get("start_date");
//        $end_date = $this->input->get("end_date");
//        $branch = $this->input->get("branch");
//        $item = $this->input->get("item");
//
//        $data['start_date'] = $start_date;
//        $data['end_date'] = $end_date;
//        $data['branch'] = $branch;
//        $data['item'] = $item;
//
//        if ($start_date != "" && $end_date != "" && $branch != "" && $item != "") {
//            /* ITEM BASIC INFO START */
//            $temp = " AND i.reaqgentfk = '$item' AND i.branchfk = '$branch'";
//
//            /* $q1 = "SELECT i.id,b.`branch_name`,r.reagent_name,s.`batch_no`,ib.brand_name as BrandName,
//              i.`quantity`,r.per_pack,s.used,i.`creteddate`,r.test_quantity,s.expire_date,
//              IF(r.category_fk = 3,r.test_quantity,r.quantity) as packet_quantity
//              FROM `inventory_usedreagent` i
//              LEFT JOIN `branch_master` b ON b.`id`=i.`branchfk`
//              LEFT JOIN `inventory_item` r ON r.`id`=i.`reaqgentfk`
//              LEFT JOIN `inventory_stock_master` s ON s.`id`=i.`indedfk`
//              LEFT JOIN inventory_brand as ib on r.brand_fk = ib.id and ib.status='1'
//              WHERE i.status='1'  $temp
//              order by i.creteddate asc";
//             */
//            //$data["query"] = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' GROUP BY s.id ORDER BY s.id ASC");
//            $data["query"] = $this->Stock_issue_report_model->get_val("SELECT b.`branch_name`,s.`id`,s.item_fk,r.`reagent_name`,r.location,r.test_quantity,r.`category_fk`,SUM(s.quantity) as totalqty,SUM(s.`used`) AS stcok,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item'  GROUP BY s.item_fk ORDER BY s.id ASC");
//
//            //$data['query'] = $this->Stock_issue_report_model->get_val($q1);
//            /* END */
//
//            //$old_opning_stock = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1 GROUP BY s.id");
//            //echo "SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1";die();
//            $old_opning_stock = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND s.`created_date`<'" . $start_date . " 00:00:00" . "' ORDER BY s.id DESC LIMIT 0,1");
//            if (!empty($old_opning_stock)) {
//                $cd = explode(" ", $old_opning_stock[0]["created_date"]);
//                if (!empty($cd)) {
//                    $new_start_date = $cd[0];
//                } else {
//                    $new_start_date = $start_date;
//                }
//            } else {
//                $od_opning = 0;
//                $new_start_date = $start_date;
//            }
////echo $new_start_date."---".$end_date;die();
////echo "<pre>";print_r($old_opning_stock);die();
//
//            $end_date1 = date('Y-m-d', strtotime($end_date . ' +1 day'));
//            $period = new DatePeriod(
//                    new DateTime($new_start_date), new DateInterval('P1D'), new DateTime($end_date1)
//            );
//            $dates = array();
//            foreach ($period as $key => $value) {
//                $dates[] = $value->format('Y-m-d');
//            }
//
//
//
//            /* GET Pack wise reagent STart */
//            //$reagent_pack = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_usedreagent` WHERE `status`='1' AND `branchfk`='" . $branch . "' AND reaqgentfk='" . $item . "'");
//
//            $reagent_pack = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,r.test_quantity,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' ORDER BY s.id ASC");
//            //echo "<pre>"; print_r($reagent_pack);die();  
//            $sdate = "1999-01-01";
//            $cnt = 0;
//            $alength = count($reagent_pack);
//            foreach ($reagent_pack as $rpkey) {
//                if ($alength > $cnt + 1) {
//                    $edate = $reagent_pack[$cnt + 1]["created_date"];
//                } else {
//                    $edate = date("Y-m-d H:i:s");
//                }
//                $reagent_test = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_jobstock` WHERE `status`='1' AND branchid='" . $branch . "' AND `itemfk`='" . $item . "' and creteddate>='" . $sdate . "' and creteddate<='" . $edate . "' order by creteddate");
//
//                $reagent_pack1 = $this->Stock_issue_report_model->get_val("SELECT * FROM `inventory_usedreagent` WHERE `status`='1' AND `branchfk`='" . $branch . "' AND reaqgentfk='" . $item . "' and creteddate>='" . $sdate . "' and creteddate<='" . $edate . "' order by creteddate");
//                //$reagent_test = $this->Stock_issue_report_model->get_val("SELECT s.`id`,s.item_fk,s.batch_no,s.`expire_date`,s.`created_date`,s.`quantity`,s.`used` AS stcok,b.`branch_name`,s.`id`,r.`reagent_name`,r.`category_fk`,inw.`branch_fk` FROM inventory_stock_master s INNER JOIN `inventory_item` r ON r.`id`=s.`item_fk` INNER JOIN inventory_inward_master inw ON inw.`id`=s.`inward_fk` INNER JOIN `branch_master` b ON b.`id`=inw.`branch_fk` WHERE s.`status`='1' and inw.branch_fk='$branch' AND s.item_fk='$item' AND created_date>='" . $sdate . "' and created_date<='" . $edate . "'");
//
//
//                $sdate = $rpkey["created_date"];
//                $reagent_pack[$cnt]["ur"] = $reagent_pack1[0]["id"];
//                $reagent_pack[$cnt]["perform_test"] = $reagent_test;
//                $cnt++;
//            }
//            /* END */
//
//            //echo "<pre>";
//            //print_r($reagent_pack);
//            //die();
//
//            $new_array = array();
//            $cnt = 0;
//            $cnt1 = 0;
//            $old_opning = 0;
//
//
//            /*
//              foreach ($dates as $key) {
//              $open_stock = $old_opning;
//              $closing_stock = $old_opning;
//              $issue = 0;
//              $received = 0;
//              $new_bottol_time = "";
//              $new_bottol_test = 0;
//              foreach ($reagent_pack as $dkey) {
//              if ($key . " 00:00:00" <= $dkey["created_date"] && $key . " 23:59:59" >= $dkey["created_date"]) {
//              //die("OK");
//              $received = $data['query'][0]["test_quantity"] * $dkey["quantity"];
//              //echo $received; die();
//              $old_opning = $old_opning + $dkey["quantity"];
//              $closing_stock = $old_opning + $received;
//              $new_bottol_time = $dkey["created_date"];
//              }
//              $ccnt = 0;
//              foreach ($dkey["perform_test"] as $ptsKey) {//echo "<pre>"; print_r($dkey);die();
//              if ($key . " 00:00:00" <= $ptsKey["creteddate"] && $key . " 23:59:59" >= $ptsKey["creteddate"]) {
//              $issue = $issue + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
//              if (!empty($new_bottol_time) && $new_bottol_time <= $ptsKey["creteddate"]) {
//              $new_bottol_test = $new_bottol_test + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
//              if ($ccnt == 0) {
//              $closing_stock = $received;
//              }
//              }
//              //if (empty($new_bottol_time)) {
//              $closing_stock = $closing_stock - ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
//              //} else {
//              //$closing_stock = $closing_stock - $new_bottol_test;
//              //}
//              }
//              }
//              }
//              if ($key . " 00:00:00" >= $start_date) {
//              $new_array[] = array("srno" => $cnt + 1, "date" => $key, "compony" => $data['query'][0]["BrandName"], "pack_size" => $data['query'][0]["test_quantity"], "lot" => $data['query'][0]["batch_no"], "expire_date" => $data['query'][0]["expire_date"], "opening" => $open_stock, "received" => $received, "issue" => $issue, "new_bottol_test" => $new_bottol_test, "closing" => $closing_stock);
//              }
//              //if ($issue > 0) {
//              $old_opning = $closing_stock;
//              //}
//              }
//             */
//
//
//            $use_ragent1 = 0;
//            $use_reagent_cnt = 0;
//            $udm = 0;
//            $NewStock = 0;
//            $westage = 0;
//            foreach ($dates as $key) {
//                $open_stock = $old_opning;
//                $closing_stock = $old_opning;
//                $issue = 0;
//                $received = 0;
//                $new_bottol_time = "";
//                $new_bottol_test = 0;
//
//                $myregent = $this->Stock_issue_report_model->get_val("SELECT inventory_usedreagent.*,`inventory_item`.`reagent_name`,`inventory_item`.`test_quantity` FROM `inventory_usedreagent` LEFT JOIN `inventory_item` ON `inventory_item`.`id`=inventory_usedreagent.`reaqgentfk` WHERE inventory_usedreagent.`status`='1' AND inventory_usedreagent.`branchfk`='" . $branch . "' AND inventory_usedreagent.reaqgentfk='" . $item . "' AND inventory_usedreagent.creteddate LIKE '%" . $key . "%' ORDER BY inventory_usedreagent.creteddate");
//
//                if (count($myregent) > 0) {
//                    $wastage = $NewStock;
//                    $NewStock = $myregent[0]["test_quantity"];
//                }
//                foreach ($reagent_pack as $dkey) {
//
//                    if ($key . " 00:00:00" <= $dkey["created_date"] && $key . " 23:59:59" >= $dkey["created_date"]) {
//                        $received = $dkey["test_quantity"] * $dkey["quantity"];
//                        $old_opning = $old_opning + $dkey["quantity"];
//                        $closing_stock = $received;
//                        $new_bottol_time = $dkey["created_date"];
//                    }
//                    $ccnt = 0;
//
//                    foreach ($dkey["perform_test"] as $ptsKey) {
//                        //echo $ptsKey["usedreagent_fk"]."---".$use_reagent_cnt."<br>";
//                        //echo "<br>";
//                        //echo $NewStock . '-' . $westage;
//                        if ($use_ragent1 != $ptsKey["usedreagent_fk"]) {
//                            $use_ragent1 = $ptsKey["usedreagent_fk"];
//                            $udm = $use_reagent_cnt;
//                            $use_reagent_cnt = 0;
//                            //echo "OK<br>";
//                            //                      $westage = $NewStock;
//                            //              print_r($ptsKey);
//                            if (date('Y-m-d', strtotime($key)) == date('Y-m-d', strtotime($ptsKey["creteddate"]))) {
//                                //                        $wastage= $westage;
//                                //                    echo   $key;
//                                //                  die("test");
//                                //                    
//                            }
//                            // $NewStock = $dkey["test_quantity"];
//                        }
//
//                        if ($key . " 00:00:00" <= $ptsKey["creteddate"] && $key . " 23:59:59" >= $ptsKey["creteddate"]) {
//                            $issue = $issue + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
//                            if (!empty($new_bottol_time) && $new_bottol_time <= $ptsKey["creteddate"]) {
//                                $new_bottol_test = $new_bottol_test + ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
//                                if ($ccnt == 0) {
//                                    //$closing_stock = $received;
//                                }
//                            }
//                            if (empty($new_bottol_time)) {
//                                $closing_stock = $open_stock + $received - ($ptsKey["timeperfomace"] * $ptsKey["useditem"]);
//                            } else {
//                                $closing_stock = $open_stock + $received - $new_bottol_test;
//                            }
//                        }
//                        $closing_stock = $open_stock + $received - $issue;
//                        //echo $use_reagent_cnt."----".$cnt1;die();
//                        if ($use_ragent1 != $ptsKey["usedreagent_fk"]) {
//                            $use_reagent_cnt = $use_reagent_cnt + $issue;
//                        }
//                        if ($use_reagent_cnt == 0 && $cnt1 != 0) {
//                            $rred = $dkey["quantity"] - $udm;
//                            //$closing_stock = $closing_stock - $rred;
//                        }
//                        // echo "<br/>".$NewStock."- ". $issue;
//                        // $NewStock = $NewStock - $issue;
//
//                        $cnt1++;
//                    }
//                }
//                //echo $use_reagent_cnt . "--" . $issue . "---" . $closing_stock . "---" . $received;
//                //die();
//                if ($key . " 00:00:00" >= $start_date) {
//                    $NewStock = $NewStock - $issue;
//                    $new_array[] = array("wastage" => $wastage, "srno" => $cnt + 1, "date" => $key, "compony" => $data['query'][0]["BrandName"], "pack_size" => $data['query'][0]["test_quantity"], "lot" => $data['query'][0]["batch_no"], "expire_date" => $data['query'][0]["expire_date"], "opening" => $open_stock, "received" => $received, "issue" => $issue, "new_bottol_test" => $new_bottol_test, "closing" => $closing_stock - $wastage, "westage" => $udm);
//                }
//                $wastage = 0;
//                //if ($issue > 0) {
//                $old_opning = $closing_stock;
//                //}
//            }
//
//
//
//            //echo "<pre>";
//            //print_r($new_array);
//            //die();
//
//            $data["new_array"] = $new_array;
//        }
//
//        $date = date("_Y-m-d_H:i:s");
//        $pdfFilePath = FCPATH . "/upload/employee/stock_issue_report" . $date . ".pdf";
//
//        ini_set('memory_limit', '128M');
//
//        $html = $this->load->view('inventory/stock_issue_report_pdf', $data, true);
//
//
//        if (file_exists($pdfFilePath)) {
//            $this->delete_downloadfile($pdfFilePath);
//        }
//
//        $this->load->library('pdf');
//        $pdf = $this->pdf->load();
//        $pdf->autoScriptToLang = true;
//        $pdf->baseScript = 1; // Use values in classes/ucdn.php  1 = LATIN
//        $pdf->autoVietnamese = true;
//        $pdf->autoArabic = true;
//        $pdf->autoLangToFont = true;
//
//        /* $pdf->SetHTMLHeader('<body>
//          <div class="pdf_container">
//          <div class="main_set_pdng_div">
//          <div class="brdr_full_div">
//          <div class="header_full_div">
//          <img class="set_logo" src="logo.png" style="margin-top:15px;"/>
//          </div>'); */
//
//        $pdf->AddPage('p', // L - landscape, P - portrait
//                '', '', '', '', 5, // margin_left
//                5, // margin right
//                5, // margin top
//                5, // margin bottom
//                2, // margin header
//                2); // margin footer
//
//
//        /* $pdf->SetHTMLFooter('<div class="foot_num_div" style="margin-bottom:0;padding-bottom:0">
//          <p class="foot_num_p" style="margin-bottom:2;padding-bottom:0"><img class="set_sign" src="pdf_phn_btn.png" style="width:"/></p>
//          <p class="foot_lab_p" style="font-size:13px;margin-bottom:2;padding-bottom:0">LAB AT YOUR DOORSTEP</p>
//          </div>
//          <p class="lst_airmed_mdl" style="font-size:13px;margin-top:5px">Airmed Pathology Pvt. Ltd.</p>
//          <p class="lst_31_addrs_mdl" style="font-size:12px"><span style="color:#9D0902;">Commercial Address : </span>31, Ambika Society, Next to Nabard Bank, Opp. Usmanpura, Ahmedabad, Gujarat - 380 013.</p>
//          <p class="lst_31_addrs_mdl"><b><img src="email-icon.png" style="margin-bottom:-3px;width:13px"/> info@airmedlabs.com  <img src="web-icon.png" style="margin-bottom:-3px;width:13px"/> www.airmedlabs.com</b></p><p class="lst_31_addrs_mdl"><!--<img src="lastimg.png" style="margin-top:3px;"/>--> </p></div>
//          </body>
//          </html>'); */
//
//        $data["total_page"] = count($pdf->pages);
//        //$data["page"] = $pdf->getPageCount();
//        $pdf->WriteHTML($html);
//
//        $pdf->Output("Reagent-Stock-Report-" . date("d-m-Y H:i") . ".pdf", 'D'); // save to file because we can
//
//        $downld = $this->_push_file($pdfFilePath, "stock_issue_report" . $date . ".pdf");
//        $this->session->set_flashdata("success", "Stock issue report has downloaded successfully.");
//        redirect($pdfFilePath);
//    }

    function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
        }
    }

}

?>