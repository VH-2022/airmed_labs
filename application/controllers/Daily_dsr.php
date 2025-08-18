<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Daily_dsr extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('master_model');
        $this->load->model('job_model');
        $this->load->model('user_model');
        $this->load->model('job_report_model');
        $this->load->library('email');
        $this->load->helper('string');
        $this->app_tarce();
    }

    function app_tarce() {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
        if (!empty($_SERVER['QUERY_STRING'])) {
            $page = $_SERVER['QUERY_STRING'];
        } else {
            $page = "";
        }
        if (!empty($_POST)) {
            $user_post_data = $_POST;
        } else {
            $user_post_data = array();
        }
        $user_post_data = json_encode($user_post_data);
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $remotehost = @getHostByAddr($ipaddress);
        $user_info = json_encode(array("Ip" => $ipaddress, "Page" => $page, "UserAgent" => $useragent, "RemoteHost" => $remotehost));
        if ($actual_link != "http://www.airmedlabs.com/index.php/api/send") {
            $user_track_data = array("url" => $actual_link, "user_details" => $user_info, "data" => $user_post_data, "createddate" => date("Y-m-d H:i:s"), "type" => "service");
        }
        $app_info = $this->user_model->master_fun_insert("user_track", $user_track_data);
        //return true;
    }

    function getDailyCollectionData($start_date, $branch) {
        $data["login_data"] = logindata();
        $data['start_date'] = $start_date;
        $user_branch = $this->user_model->master_fun_get_tbl_val("user_branch", array('status' => 1, "user_fk" => $data["login_data"]['id']), array("id", "asc"));
        // $branch = array();
        $start_date = null;
        $end_date = null;
        if ($data['start_date'] != "") {
            $start_date = $data['start_date'];
        }
        $DailyCollectionData = array();
        foreach ($branch as $b) {
            $data['branch'] = $b;
            $data['userlistAddedBy'] = $this->job_model->get_val("SELECT  DISTINCT( job_master.`added_by`) FROM job_master WHERE !ISNULL(job_master.`added_by`) AND job_master.model_type=1 and (job_master.`added_by`)!='0' AND job_master.`branch_fk`='" . $data['branch'] . "'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE('" . $data['start_date'] . "',    '%d/%m/%Y'  ) ");


            $data['userlistCashAddedBy2'] = $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`added_by` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.added_by)    AND ISNULL(job_master_receiv_amount.`phlebo_fk`) AND jb.model_type=1 and  job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch'] . "'");
            $data['userlistCashAddedByPhlebo'] = $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`phlebo_fk` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.`phlebo_fk`)    AND   jb.model_type=1 and  job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch'] . "'");
            $usersaddedbyall = array_merge($data['userlistAddedBy'], $data['userlistCashAddedBy2']);
            $data['branch'] = $b;
            //echo "SELECT COUNT(id) AS cnt FROM job_master WHERE DATE_FORMAT(job_master.`date`, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('" . $data['start_date'] . " 00:00:00','%d/%m/%Y %H:%i:%s') AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('" . $data['start_date'] . " 23:59:59','%d/%m/%Y %H:%i:%s') AND `status`!='0' AND branch_fk='" . $data['branch'] . "'";die();
            $job_cnt = $sameday = $this->job_model->get_val("SELECT COUNT(id) AS cnt,SUM(price) AS revenue,SUM(`payable_amount`)AS due_amount,ROUND(SUM(`discount`*`price`/100))AS discount FROM job_master WHERE DATE_FORMAT(job_master.`date`, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('" . $data['start_date'] . " 00:00:00','%d/%m/%Y %H:%i:%s') AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('" . $data['start_date'] . " 23:59:59','%d/%m/%Y %H:%i:%s') AND `status`!='0' AND branch_fk='" . $data['branch'] . "' AND `model_type`='1'");

            $addedBY = array();
            foreach ($usersaddedbyall as $o) {
                if (!empty($o['added_by'])) {
                    $addedBY[] = $o['added_by'];
                }
            }
            $creditor_user = $this->job_model->get_val("SELECT  DISTINCT created_by FROM `creditors_balance` LEFT JOIN job_master ON job_master.id=`creditors_balance`.`job_id` WHERE job_master.`status` = '1' AND `creditors_balance`.`status` = '1'   AND DATE_FORMAT(creditors_balance.`created_date`, '%Y-%m-%d')  = STR_TO_DATE('" . $data['start_date'] . "','%d/%m/%Y') AND `credit`>0  AND `job_master`.`model_type`='1'");
            foreach ($creditor_user as $oa) {
                $addedBY[] = $oa['created_by'];
            }
            $UsersData = array();
            if (count($addedBY) > 0) {
                $UsersData = $this->job_model->get_val("select * from admin_master where id in (" . implode($addedBY, ',') . ")");
            }
            $dataForPayment = array();
            $data['branchName'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "id" => $data['branch']), array("id", "asc"));
            $branch_name = $data['branchName'][0]["branch_name"];
            /*    $data["online_payment"] = $this->user_model->get_val("SELECT SUM(`job_master`.`price`) AS price,SUM(job_master.payable_amount) as payable_amount FROM `job_master` INNER JOIN `payment` ON `payment`.`job_fk`=`job_master`.`id` AND `job_master`.`price`=`payment`.`amount` WHERE  job_master.model_type=1 and  `job_master`.`status`!='0' AND `payment`.`status`='success' AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) and  `job_master`.`branch_fk`='" . $data['branch'] . "'");
             */
            $data["online_payment"] = $this->user_model->get_val("SELECT  SUM(`job_master`.`price`) AS price,SUM(job_master.payable_amount) as payable_amount  FROM `job_master`  WHERE  job_master.model_type=1 and  `job_master`.`status`!='0'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) and  `job_master`.`branch_fk`='" . $data['branch'] . "'  AND ISNULL( job_master.`phlebo_added`) AND ISNULL( job_master.`added_by`) AND `job_master`.`model_type`='1'");
            /* if($data["login_data"]['id']=="12"){
              echo $this->db->last_query();
              print_R($data['online_payment']);
              die();

              } */


            //echo $this->db->last_query(); ///die();
            if ($data["online_payment"][0]["price"] != "") {
                $u = array('username' => array("name" => "Online"), "type" => "online");
                $u["branch_name"] = $branch_name;
                $u['cash'] = 00;
                $u['gross_amount'] = $data["online_payment"][0]["price"];
                $u['discount'] = 00;
                $u['price'] = $data["online_payment"][0]["price"];
                $u['payable_amount'] = $data["online_payment"][0]["payable_amount"];
                $cash = 00;
                $u['cash_total'] = 00;
                $u['cash_ttt'] = $payments;
                $u['other_total'] = 00;
                $u['credit_total'] = 00;
                $u['creditor_total'] = 0.0;
                $u['cheque_total'] = 00;
                $u['same_day'] = 00; //($sameday[0]['price']!="")?$sameday[0]['price']:00;
                $u['back_day'] = 00; ///($backday[0]['price']!="")?$backday[0]['price']:00;;
                $u['net'] = 00;
                // print_r($data["online_payment"]);die();
                $dataForPayment[] = $u;
            }
            foreach ($UsersData as $user) {
                $u = array('username' => $user, "type" => "user");
                $u["branch_name"] = $branch_name;
                /* 		$u['cash']=
                  $this->job_model->get_val("SELECT SUM(c.amount) as 'CASH' FROM job_master_receiv_amount c LEFT JOIN job_master jb ON jb.id = c.job_fk WHERE (c.added_by='".$user['id']."') AND c.payment_type='CASH' AND DATE_FORMAT(c.`createddate`,'%Y-%m-%d')= STR_TO_DATE(    '".$data['start_date']."',    '%d/%m/%Y'  ) AND c.status='1' AND jb.branch_fk=".$data['branch']."");
                 */
                //$u["cash"]=($u["cash"][0]['CASH']=="")?"00":$u["cash"][0]['CASH'];
                $payments = $this->job_model->get_val("SELECT  c.payment_type , SUM(c.amount)  AS amount FROM   job_master_receiv_amount c   LEFT JOIN job_master jb     ON jb.id = c.job_fk WHERE     c.added_by = '" . $user['id'] . "'   AND DATE_FORMAT(c.`createddate`, '%Y-%m-%d') =STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND c.status = '1'  and jb.model_type=1    AND jb.branch_fk = " . $data['branch'] . "   GROUP BY  c.payment_type ");
                //echo $this->db->last_query()."<br>";
                $u['JOBS'] = $this->job_model->get_val("SELECT SUM(job_master.`price`) AS price,SUM(job_master.`price`*job_master.`discount`/100) AS discount,SUM(job_master.payable_amount) as payable_amount FROM job_master WHERE
				 job_master.model_type=1 and  job_master.branch_fk =" . $data['branch'] . " and  job_master.`added_by` =  '" . $user['id'] . "' AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d')=STR_TO_DATE('" . $data['start_date'] . "',    '%d/%m/%Y')");
                //echo $this->db->last_query()."<br>";
                // echo "SELECT SUM(debit) AS debit,SUM(credit) AS credit FROM `creditors_balance` WHERE `status`='1' AND DATE_FORMAT(created_date, '%Y-%m-%d') = STR_TO_DATE('" . $data['start_date'] . "', '%d/%m/%Y') AND `created_by`='" . $user['id'] . "'";
                //die();
                $u["creditors_add"] = $this->user_model->get_val("SELECT SUM(debit) AS debit,SUM(credit) AS credit FROM `creditors_balance` WHERE `status`='1' AND DATE_FORMAT(created_date, '%Y-%m-%d') = STR_TO_DATE('" . $data['start_date'] . "', '%d/%m/%Y') AND `created_by`='" . $user['id'] . "'");
                //echo "SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk and jbs.date = s.createddate AND jbs.`status`!='0' WHERE  jbs.model_type=1 and  s.added_by =  '" . $user['id'] . "' and s.payment_type!='CREDITORS'  AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND DATE_FORMAT(jbs.date  , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND s.status = '1' <br>"; die();
                $u['samedaytest'] = $sameday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s left JOIN job_master jbs   ON jbs.id = s.job_fk WHERE  jbs.model_type=1 AND jbs.`status`!='0' and  s.added_by =  '" . $user['id'] . "' and s.payment_type!='CREDITORS'  AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND DATE_FORMAT(jbs.date  , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND s.status = '1'");
                $u['backdaytest'] = $backday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE jbs.model_type=1 AND jbs.`status`!='0' and s.added_by =  '" . $user['id'] . "' AND s.payment_type!='CREDITORS'  AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND jbs.date   < STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND s.status = '1' AND jbs.`model_type`='1'");
                $u['gross_amount'] = $u['JOBS'][0]["price"];
                $u['discount'] = $u['JOBS'][0]["discount"];
                $u['price'] = $u['JOBS'][0]["price"] - $u['JOBS'][0]["discount"];
                $u['payable_amount'] = $u['JOBS'][0]["payable_amount"];
                $cash = 00;
                $u['cash_total'] = 0.0;
                $u['cash_ttt'] = $payments;
                $u['other_total'] = 0.0;
                $u['credit_total'] = 0.0;
                $u['creditor_total'] = 0.0;
                $u['cheque_total'] = 0.0;
                $u['same_day'] = ($sameday[0]['price'] != "") ? $sameday[0]['price'] : 0.0;
                $u['back_day'] = ($backday[0]['price'] != "") ? $backday[0]['price'] : 0.0;
                $u['net'] = round($u['price'] - $u['same_day'], 2);
                $u['net'] = ((int) $u['net'] < 0) ? 0.0 : $u['net'];
                foreach ($payments as $p) {
                    if ($p['payment_type'] == "CASH") {
                        $u['cash_total'] = $p['amount'];
                    } else
                    if ($p['payment_type'] == "CHEQUE") {
                        $u['cheque_total'] += $p['amount'];
                    } else if (in_array($p['payment_type'], array("CREDIT CARD", "CREDIT CARD swiped thru ICICI", 'WALLET CREDIT CARD swiped thru MSWIP', 'DEBIT CARD swiped thru ICICI', 'DEBIT CARD swiped thru MSWIP', 'Swipe thru HDFC', 'Swipe thru AXIS', 'DEBIT CARD'))) {
                        //(cr.payment_type='CREDIT CARD' or cr.payment_type='CREDIT CARD swiped thru ICICI' or cr.payment_type='CREDIT CARD swiped thru MSWIP' or cr.payment_type='DEBIT CARD swiped thru ICICI' or cr.payment_type='DEBIT CARD swiped thru MSWIP' or cr.payment_type='Swipe thru HDFC' or cr.payment_type='Swipe thru AXIS' or cr.payment_type='DEBIT CARD')
                        $u['credit_total'] += $p['amount'];
                    } else if (in_array($p['payment_type'], array("CREDITORS"))) {
                        $u['creditor_total'] += $p['amount'];
                    } else {
                        $u['other_total'] += $p['amount'];
                    }
                }


                $dataForPayment[] = $u;
            }

            if (!empty($data['branch'])) {
                $phleboid = $this->job_model->get_val("SELECT  DISTINCT( job_master.`phlebo_added`) FROM job_master WHERE !ISNULL(job_master.`phlebo_added`) AND (job_master.`phlebo_added`)!='0' AND job_master.model_type=1 and job_master.`branch_fk`='" . $data['branch'] . "'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )");
                $phleboid += $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`phlebo_fk` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.phlebo_fk)   AND jb.model_type=1 and   job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch'] . "'");
                $addedBY = array();
                foreach ($data['userlistCashAddedByPhlebo'] as $o) {
                    if ($o['phlebo_fk'] != "") {
                        //echo $o['phlebo_fk']; die("Phlebo_added");
                        $addedBY[] = $o['phlebo_fk'];
                    }
                }
            }
            /* print_r( $addedBY);
              echo implode(  $addedBY,',' );
              echo "select * from admin_user where id in (".implode(  $data['userlistAddedBy'],',' ).")";
             */
            $UsersData = array();
            if (count($addedBY) > 0) {
                $UsersData = $this->job_model->get_val("SELECT * FROM  `phlebo_master`  where id in (" . implode($addedBY, ',') . ")");
            }
            foreach ($UsersData as $user) {
                $u = array('username' => $user, "type" => "phlebo");
                $u["branch_name"] = $branch_name;
                $u['cash'] = $this->job_model->get_val("SELECT SUM(c.amount) as 'CASH' FROM job_master_receiv_amount c LEFT JOIN job_master jb ON jb.id = c.job_fk WHERE (c.phlebo_fk='" . $user['id'] . "') AND  jb.model_type=1 and  c.payment_type='CASH' AND DATE_FORMAT(c.`createddate`,'%Y-%m-%d')= STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) AND c.status='1' AND jb.branch_fk=" . $data['branch'] . "");
                //$u["cash"]=($u["cash"][0]['CASH']=="")?"00":$u["cash"][0]['CASH'];
                $payments = $this->job_model->get_val("SELECT  c.payment_type , SUM(c.amount)  AS amount FROM   job_master_receiv_amount c   LEFT JOIN job_master jb     ON jb.id = c.job_fk WHERE     c.phlebo_fk = '" . $user['id'] . "'   AND DATE_FORMAT(c.`createddate`, '%Y-%m-%d') =STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND c.status = '1'  and jb.model_type=1  AND jb.branch_fk = " . $data['branch'] . "   GROUP BY  c.payment_type ");
                //echo $this->db->last_query()."<br>";
                $u['JOBS'] = $this->job_model->get_val("SELECT SUM(job_master.`price`) AS price,SUM(job_master.`price`*job_master.`discount`/100) AS discount,SUM(job_master.payable_amount) as payable_amount FROM job_master WHERE
				job_master.branch_fk =" . $data['branch'] . " and job_master.model_type=1 and   job_master.`phlebo_added` =  '" . $user['id'] . "' AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d')=STR_TO_DATE('" . $data['start_date'] . "',    '%d/%m/%Y')");
                //echo $this->db->last_query()."<br>";
                $u['samedaytest'] = $sameday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE jbs.model_type=1 and  s.phlebo_fk =  '" . $user['id'] . "'  and s.payment_type!='CREDITORS' AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND DATE_FORMAT(jbs.date  , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND s.status = '1' ");
                $u['backdaytest'] = $backday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE  jbs.model_type=1 and  s.phlebo_fk =  '" . $user['id'] . "'   AND jbs.branch_fk =" . $data['branch'] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND jbs.date   < STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND s.status = '1' ");
                $u['gross_amount'] = $u['JOBS'][0]["price"];
                $u['discount'] = $u['JOBS'][0]["discount"];
                $u['price'] = $u['JOBS'][0]["price"] - $u['JOBS'][0]["discount"];
                $u['payable_amount'] = $u['JOBS'][0]["payable_amount"];
                $cash = 00;
                $u['cash_total'] = 0.0;
                $u['other_total'] = 0.0;
                $u['credit_total'] = 0.0;
                $u['creditor_total'] = 0.0;
                $u['cheque_total'] = 0.0;
                $u['same_day'] = ($sameday[0]['price'] != "") ? $sameday[0]['price'] : 0.0;
                $u['back_day'] = ($backday[0]['price'] != "") ? $backday[0]['price'] : 0.0;
                //$u['net']=$u['price']-$u['same_day'];
                $u['net'] = round($u['price'] - $u['same_day'], 2);
                $u['net'] = ((int) $u['net'] < 0) ? 0.0 : $u['net'];
                foreach ($payments as $p) {
                    if ($p['payment_type'] == "CASH") {
                        $u['cash_total'] = $p['amount'];
                    } else
                    if ($p['payment_type'] == "CHEQUE") {
                        $u['cheque_total'] += $p['amount'];
                    } else if (in_array($u['payment_type'], array('CREDIT CARD', 'CREDIT CARD swiped thru ICICI', 'WALLET CREDIT CARD swiped thru MSWIP', 'DEBIT CARD swiped thru ICICI', 'DEBIT CARD swiped thru MSWIP', 'Swipe thru HDFC', 'Swipe thru AXIS', 'DEBIT CARD'))) {
                        //(cr.payment_type='CREDIT CARD' or cr.payment_type='CREDIT CARD swiped thru ICICI' or cr.payment_type='CREDIT CARD swiped thru MSWIP' or cr.payment_type='DEBIT CARD swiped thru ICICI' or cr.payment_type='DEBIT CARD swiped thru MSWIP' or cr.payment_type='Swipe thru HDFC' or cr.payment_type='Swipe thru AXIS' or cr.payment_type='DEBIT CARD')
                        $u['credit_total'] += $p['amount'];
                    } else if (in_array($p['payment_type'], array("CREDITORS"))) {
                        $u['creditor_total'] += $p['amount'];
                    } else {
                        $u['other_total'] += $p['amount'];
                    }
                }
                $dataForPayment[] = $u;
            }
            $BrnachDailyCollectionData[] = array("branch" => $b, "branch_name" => $branch_name, "total_job_count" => $job_cnt, "dailyCollectionData" => $dataForPayment);
        }
        $dataforcollection = array();
        //echo "<prE>"; print_r($BrnachDailyCollectionData); die();
        return $BrnachDailyCollectionData;
    }

    /* function send_report() {
      ini_set('memory_limit', '128M');
      ini_set('max_execution_time', 300); //300 seconds = 5 minutes
      $this->load->library('curl');
      $persion_list = $this->user_model->master_fun_get_tbl_val("daily_collection_report_persion", array('status' => 1), array("id", "asc"));
      foreach ($persion_list as $key) {
      $this->daily_export2($key["email"], date("d/m/Y"), explode(",", trim($key["city"])), $key["branch"]);
      $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendconvert_report?email=' . $key["email"] . '&date=' . date("Y-m-d"));
      $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbusiness_report?email=' . $key["email"] . '&date=' . date("Y-m-d"));
      $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbdereport_csv?email=' . $key["email"] . '&date=' . date("Y-m-d"));
      }
      $this->daily_export3($key["email"], date("d/m/Y"), "2", "2");
      echo "Email successfully send.";
      } */

    function send_report() {
        ini_set('memory_limit', '128M');
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $this->load->library('curl');
        $persion_list = $this->user_model->master_fun_get_tbl_val("daily_collection_report_persion", array('status' => 1), array("id", "asc"));
        $email_array = array();
        foreach ($persion_list as $key) {
            $email_array[] = $key["email"];
        }
        //$email_array = array("nishit@virtualheight.com","nishit.patel@airmedlabs.com"); 
        //print_r($email_array); die();
        $this->daily_export2($email_array, date("d/m/Y"), explode(",", trim($key["city"])), $key["branch"]);
        $this->daily_export4($email_array, date("d/m/Y"), explode(",", trim($key["city"])), $key["branch"]);
        $url_parsing_array = array("email" => $email_array, "date" => date("Y-m-d"));
        $get_pass_data = http_build_query($url_parsing_array);
        /* $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendconvert_report?' . $get_pass_data);
          $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbusiness_report?' . $get_pass_data);
          $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbdereport_csv?' . $get_pass_data); */
        $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/get_all_bde_reports?' . $get_pass_data);
        $this->daily_export3($key["email"], date("d/m/Y"), "2", "2");
        echo "Email successfully send.";
    }

    function send_report_test() {
        ini_set('memory_limit', '128M');
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $this->load->library('curl');
        $persion_list = $this->user_model->master_fun_get_tbl_val("daily_collection_report_persion", array('status' => 1), array("id", "asc"));
        $email_array = array();
        foreach ($persion_list as $key) {
            $email_array[] = $key["email"];
        }
        $url_parsing_array = array("email" => $email_array, "date" => date("Y-m-d"));
        $get_pass_data = http_build_query($url_parsing_array);

        $this->curl->simple_get("http://airmedpathlabs.info/sales/reports/sendconvert_report?email%5B3%5D=nishit.patel%40airmedlabs.com&email%5B5%5D=hiten%40virtualheight.com&date=2017-12-24");
        echo 'http://airmedpathlabs.info/sales/reports/sendconvert_report?' . $get_pass_data;
        die();
//$this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendconvert_report?' . $get_pass_data);
        //$this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbusiness_report?email=' . $email_array . '&date=' . date("Y-m-d"));
        //$this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbdereport_csv?email=' . $email_array . '&date=' . date("Y-m-d"));
        echo "Email successfully send.";
    }

    function send_report1() {
        ini_set('memory_limit', '128M');
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $this->load->library('curl');
        $persion_list = $this->user_model->master_fun_get_tbl_val("daily_collection_report_persion", array('status' => 1), array("id", "asc"));
        $email_array = array();
        foreach ($persion_list as $key) {
            $email_array[] = $key["email"];
            $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendconvert_report?email=' . $key["email"] . '&date=' . date("Y-m-d"));
            $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbusiness_report?email=' . $key["email"] . '&date=' . date("Y-m-d"));
            $this->curl->simple_get('http://airmedpathlabs.info/sales/reports/sendbdereport_csv?email=' . $key["email"] . '&date=' . date("Y-m-d"));
        }
        echo "Email successfully send.";
    }

    function daily_export2($email = null, $start = null, $city = null, $branch = null) {
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');

        /* $email = "nishit@virtualheight.com";
          $start = "02/02/2018";
          $city = array("5");
          $branch = "8";
         */
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Daily_report_" . $start . ".csv";
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }
        if ($city[0] != '') {
            //$city_branch = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "city" => $city), array("id", "asc"));
            $city_branch = $this->user_model->get_val("select * from branch_master where status='1' and city in (" . implode(",", $city) . ")");
        } else {
            $city_branch = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
        }

        $branch1 = array();

        //print_R($data["login_data"]['branch_fk']);
        foreach ($city_branch as $b) {
            $branch1[] = $b['id'];
        }
        if (!empty($branch)) {
            $branch1 = explode(",", $branch);
        } else {
            
        }
        //print_R($branch1); die();

        $data["dailyCollectionData"] = $this->getDailyCollectionData($start, ($branch1));
        $output = array();
        foreach ($data["dailyCollectionData"] as $branchData) {
            foreach ($branchData['dailyCollectionData'] as $am_br) {
                /* $u['gross_amount']=$u['JOBS'][0]["price"];
                  $u['discount']=$u['JOBS'][0]["discount"];
                  $u['price']=$u['JOBS'][0]["price"]-$u['JOBS'][0]["discount"];
                  $cash=00;
                  $u['cash']=00;
                  $u['other_total']=00;
                  $u['credit_total']=00;
                  $u['cheque_total']=00;
                  $u['same_day']=($sameday[0]['price']!="")?$sameday[0]['price']:00;
                  $u['back_day']=($backday[0]['price']!="")?$backday[0]['price']:00;;
                  $u['net']=$u['price']-$u['same_day']; */
                $crd_total = 0.0;
                $crd_due = 0.0;
                $crd_total = round($am_br["creditor_total"]);
                $crd_due = round($am_br["creditors_add"][0]["credit"]);
                $new_net += abs(($crd_total - $am_br["net"]) + $crd_due);

                $output[] = array(
                    "Branch" => $branchData['branch_name'],
                    "Received_By" => $am_br['username']['name'],
                    "Gross_Amt" => ($am_br["gross_amount"] == "") ? "00" : round($am_br["gross_amount"]),
                    "Discount" => ($am_br["discount"] == "") ? "00" : round($am_br["discount"]),
                    "Net_Amt" => ($am_br["price"] == "") ? "00" : round($am_br["price"]),
                    "cash" => ($am_br["cash_total"] == "") ? "00" : round($am_br["cash_total"]),
                    "Cheque" => ($am_br["cheque_total"] == "") ? "00" : round($am_br["cheque_total"]),
                    "Credit_Debit" => ($am_br["credit_total"] == "") ? "00" : round($am_br["credit_total"]),
                    "Other_Credit" => ($am_br["other_total"] == "") ? "00" : round($am_br["other_total"]),
                    "Same_Day" => ($am_br["same_day"] == "") ? "00" : round($am_br["same_day"]),
                    "Back_Day" => ($am_br["back_day"] == "") ? "00" : round($am_br["back_day"]),
                    "Creditor_Total" => ($crd_total == "") ? "00" : round($crd_total),
                    "Creditor_Due" => ($crd_due == "") ? "00" : round($crd_due),
                    "Net" => ($am_br["payable_amount"] == "") ? "00" : round($am_br["payable_amount"], 2),
                    "all_job_count" => ($branchData["total_job_count"][0]["cnt"] == "") ? "0" : $branchData["total_job_count"][0]["cnt"],
                    "revenue" => ($branchData["total_job_count"][0]["revenue"] == "") ? "0" : $branchData["total_job_count"][0]["revenue"],
                    "due_amount" => ($branchData["total_job_count"][0]["due_amount"] == "") ? "0" : $branchData["total_job_count"][0]["due_amount"],
                    "discount" => ($branchData["total_job_count"][0]["discount"] == "") ? "0" : $branchData["total_job_count"][0]["discount"]
                );
            }
        }
        // echo "<pre>";print_r($output); die();
        $message1 = '<div style="padding:0 4%;">';
        $message1 .= '<table id="customers" style=" border-collapse: collapse; width: 100%;">
<tr>
    <td style="border: 1px solid #ddd; padding: 8px;" colspan="13"><b>Branch Wise Report</b></td>
  </tr>  
<tr>
      </tr>';
        $brnch = array();
        $q = 0;
        foreach ($output as $value) {

            if (!in_array($value["Branch"], $brnch)) {
                if ($q != 0) {
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Booking</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">' . $total_job . '</td>
  </tr>';
                    $message1 .= '<tr >
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Revenue</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $revenue . '</td>
  </tr>';
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Collection</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $collected . '</td>
  </tr>';
                    $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s SameDate Collection</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $sameday . '</td>
  </tr>';
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s BackDate Collection</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $backday . '</td>
  </tr>';
                    $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Creditor Amount</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $creditor_receiv . '</td>
  </tr>';
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Discount</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $total_discount . '</td>
  </tr>';
                    $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Due</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $dueAmount . '</td>
  </tr>';

                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>To Be Deposit Cash</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $diposit_cash . '</td>
  </tr>';
                    $message1 .= '<tr><td style="border: 1px solid #ddd; padding: 8px;" colspan="2" height="60px;"></td>';
                }
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;" colspan="2"><b>' . $value["Branch"] . '</b></td>
  </tr>';
                $gross = 0;
                $collected = 0;
                $sameday = 0;
                $backday = 0;
                $diposit_cash = 0;
                $creditor_receiv = 0;
                $discount = 0;
                $due = 0;
                $total_job = 0;
                $revenue = 0;
                $dueAmount = 0;
                $total_discount = 0;
            }
            $ttl = 0;
            $gross += $value["Gross_Amt"];
            $collected += $value["cash"] + $value["Creditor"] + $value["Other_Credit"] + $value["Credit_Debit"];
            $diposit_cash += $value["cash"];
            $sameday += $value["Same_Day"];
            $backday += $value["Back_Day"];
            $creditor_receiv += $value["Creditor_Total"];
            $discount += $value["Discount"];
            $due += $value["Net"];
            $ttl = $value["cash"] + $value["Creditor"] + $value["Other_Credit"] + $value["Credit_Debit"];
            $total_job = $value["all_job_count"];
            $revenue = $value["revenue"];
            $dueAmount = $value["due_amount"];
            $total_discount = $value["discount"];
            if ($ttl > 0) {
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;">' . $value["Received_By"] . '</td>  
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $ttl . '</td>
  </tr>';
            }
            $brnch[] = $value["Branch"];
            $q++;
            if (count($output) == $q) {
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Booking</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">' . $total_job . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Revenue</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $revenue . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Collection</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $collected . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s SameDate Collection</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $sameday . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s BackDate Collection</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $backday . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Creditor Amount</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $creditor_receiv . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Discount</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $total_discount . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Due</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $dueAmount . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>To Be Deposit Cash</b></td> 
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $diposit_cash . '</td>
  </tr>';
            }
        }
        //echo $q; die();
        $message1 .= '</table></div>';
        $message1 = $email_cnt->get_design($message1);
        echo $message1;
        $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
        $this->email->to($email);
        $this->email->subject('Daily Branch Report');
        $this->email->message($message1);
        $this->email->send();
        echo $this->email->print_debugger();
    }

    /* Send report CRUD start */

    function daily_export4($email = null, $start = null, $city = null, $branch = null) {
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $email = "piyush.gupta@airmedpathlabs.com,amit.gupta@airmedpathlabs.com";
        //$email = "nishit@virtualheight.com,kana@virtualheight.com";
        $start = date("d/m/Y");
         
        $date1 = explode("/", $start);
        $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
        $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
        $back_date = $bd . " 00:00:00";
        $back_date1 = $bd . " 23:59:59";
        $start_date = $sd . " 00:00:00";
        $end_date = $sd . " 23:59:59";
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Daily_report_" . $start . ".csv";
        if ($this->session->flashdata("error")) {
            $data["error"] = $this->session->flashdata("error");
        }
        $data["login_data"] = logindata();
        if ($data["login_data"]['type'] == 2) {
            //   redirect('Admin/Telecaller');
        }
        if ($city[0] != '') {
            //$city_branch = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "city" => $city), array("id", "asc"));
            $city_branch = $this->user_model->get_val("select * from branch_master where status='1' and branch_type_fk in (2,3,4)");
        } else {
            $city_branch = $this->user_model->get_val("select * from branch_master where status='1' and branch_type_fk in (2,3,4)");
        }

        $branch1 = array();

        //print_R($data["login_data"]['branch_fk']);
        foreach ($city_branch as $b) {
            $branch1[] = $b['id'];
        }
        //echo implode(",",$branch1);die();

        $data["dailyCollectionData"] = $this->getDailyCollectionData($start, ($branch1));
        //echo "<pre>"; print_R($data["dailyCollectionData"]);die();
        $output = array();
        foreach ($data["dailyCollectionData"] as $branchData) {
            foreach ($branchData['dailyCollectionData'] as $am_br) {
                
                /* $u['gross_amount']=$u['JOBS'][0]["price"];
                  $u['discount']=$u['JOBS'][0]["discount"];
                  $u['price']=$u['JOBS'][0]["price"]-$u['JOBS'][0]["discount"];
                  $cash=00;
                  $u['cash']=00;
                  $u['other_total']=00;
                  $u['credit_total']=00;
                  $u['cheque_total']=00;
                  $u['same_day']=($sameday[0]['price']!="")?$sameday[0]['price']:00;
                  $u['back_day']=($backday[0]['price']!="")?$backday[0]['price']:00;;
                  $u['net']=$u['price']-$u['same_day']; */
                $crd_total = 0.0;
                $crd_due = 0.0;
                $crd_total = round($am_br["creditor_total"]);
                $crd_due = round($am_br["creditors_add"][0]["credit"]);
                $new_net += abs(($crd_total - $am_br["net"]) + $crd_due);
                $output[] = array(
                    "Branch" => $branchData['branch_name'],
                    "Branch_id" => $branchData['branch'],
                    "Received_By" => $am_br['username']['name'],
                    "Gross_Amt" => ($am_br["gross_amount"] == "") ? "00" : round($am_br["gross_amount"]),
                    "Discount" => ($am_br["discount"] == "") ? "00" : round($am_br["discount"]),
                    "Net_Amt" => ($am_br["price"] == "") ? "00" : round($am_br["price"]),
                    "cash" => ($am_br["cash_total"] == "") ? "00" : round($am_br["cash_total"]),
                    "Cheque" => ($am_br["cheque_total"] == "") ? "00" : round($am_br["cheque_total"]),
                    "Credit_Debit" => ($am_br["credit_total"] == "") ? "00" : round($am_br["credit_total"]),
                    "Other_Credit" => ($am_br["other_total"] == "") ? "00" : round($am_br["other_total"]),
                    "Same_Day" => ($am_br["same_day"] == "") ? "00" : round($am_br["same_day"]),
                    "Back_Day" => ($am_br["back_day"] == "") ? "00" : round($am_br["back_day"]),
                    "Creditor_Total" => ($crd_total == "") ? "00" : round($crd_total),
                    "Creditor_Due" => ($crd_due == "") ? "00" : round($crd_due),
                    "Net" => ($am_br["payable_amount"] == "") ? "00" : round($am_br["payable_amount"], 2),
                    "all_job_count" => ($branchData["total_job_count"][0]["cnt"] == "") ? "0" : $branchData["total_job_count"][0]["cnt"],
                    "revenue" => ($branchData["total_job_count"][0]["revenue"] == "") ? "0" : $branchData["total_job_count"][0]["revenue"],
                    "due_amount" => ($branchData["total_job_count"][0]["due_amount"] == "") ? "0" : $branchData["total_job_count"][0]["due_amount"],
                    "discount" => ($branchData["total_job_count"][0]["discount"] == "") ? "0" : $branchData["total_job_count"][0]["discount"]
                );
            }
        }
        // echo "<pre>";print_r($output); die();
        $message1 = '<div style="padding:0 4%;">';
        $message1 .= '<table id="customers" style=" border-collapse: collapse; width: 100%;">
<tr>
    <td style="border: 1px solid #ddd; padding: 8px;" colspan="13"><b>Branch Wise Report</b></td>
  </tr>  
<tr>
      </tr>';
        $brnch = array();
        $q = 0;
        foreach ($output as $value) {
            if (!in_array($value["Branch"], $brnch)) {
                if ($q != 0) {
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Booking</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">' . $total_job . '</td>
  </tr>';
                    $message1 .= '<tr >
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Revenue</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $revenue . '</td>
  </tr>';
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Collection</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $collected . '</td>
  </tr>';
                    $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s SameDate Collection</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $sameday . '</td>
  </tr>';
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s BackDate Collection</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $backday . '</td>
  </tr>';
                    $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Creditor Amount</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $creditor_receiv . '</td>
  </tr>';
                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Discount</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $total_discount . '</td>
  </tr>';
                    $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;"><b>Today\'s Due</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $dueAmount . '</td>
  </tr>';

                    $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd; padding: 8px;"><b>To Be Deposit Cash</b></td>
    <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $diposit_cash . '</td>
  </tr>';
                    $message1 .= '<tr><td style="border: 1px solid #ddd; padding: 8px;" colspan="2" height="60px;"></td>';
                }
                $branch_details = $this->user_model->get_val("select branch_type.name from branch_master inner join branch_type on branch_type.id=branch_master.branch_type_fk where branch_master.status='1' and branch_master.id='".$value["Branch_id"]."'");
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd; padding: 8px;" colspan="2"><b>' . $value["Branch"] . ' - '.$branch_details[0]["name"].'</b></td>
  </tr>';
                $gross = 0;
                $collected = 0;
                $sameday = 0;
                $backday = 0;
                $diposit_cash = 0;
                $creditor_receiv = 0;
                $discount = 0;
                $due = 0;
                $total_job = 0;
                $revenue = 0;
                $dueAmount = 0;
                $total_discount = 0;
            }
            $ttl = 0;
            $gross += $value["Gross_Amt"];
            $collected += $value["cash"] + $value["Creditor"] + $value["Other_Credit"] + $value["Credit_Debit"];
            $diposit_cash += $value["cash"];
            $sameday += $value["Same_Day"];
            $backday += $value["Back_Day"];
            $creditor_receiv += $value["Creditor_Total"];
            $discount += $value["Discount"];
            $due += $value["Net"];
            $ttl = $value["cash"] + $value["Creditor"] + $value["Other_Credit"] + $value["Credit_Debit"];
            $total_job = $value["all_job_count"];
            $revenue = $value["revenue"];
            $dueAmount = $value["due_amount"];
            $total_discount = $value["discount"];
            if ($ttl > 0) {
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;">' . $value["Received_By"] . '</td>  
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $ttl . '</td>
  </tr>';
            }
            $brnch[] = $value["Branch"];
            $q++;
            if (count($output) == $q) {
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Booking</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">' . $total_job . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Revenue</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $revenue . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Collection</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $collected . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s SameDate Collection</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $sameday . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s BackDate Collection</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $backday . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Creditor Amount</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $creditor_receiv . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Discount</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $total_discount . '</td>
  </tr>';
                $message1 .= '<tr>
    <td style="border: 1px solid #ddd;padding: 8px;"><b>Today\'s Due</b></td>
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $dueAmount . '</td>
  </tr>';
                $message1 .= '<tr style="background-color: #f2f2f2;">
    <td style="border: 1px solid #ddd;padding: 8px;"><b>To Be Deposit Cash</b></td> 
    <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $diposit_cash . '</td>
  </tr>';
            }
        }
        $message1 .= '</table></div>';
        $message1 = $email_cnt->get_design($message1);
        $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
        $this->email->to($email);
        $this->email->subject('Daily PSC,FPSC,SNS Branch Report');
        $this->email->message($message1);
        $this->email->send();
        echo $this->email->print_debugger();
    }

    function user_list() {
        if (!is_loggedin()) {
            redirect('login');
        }

        $data["login_data"] = logindata();
        $data["user"] = $this->user_model->getUser($data["login_data"]["id"]);
        $data['success'] = $this->session->flashdata("success");

        $data["query"] = $this->user_model->get_val("SELECT `daily_collection_report_persion`.* FROM `daily_collection_report_persion` WHERE `daily_collection_report_persion`.`status`='1'");
        $data['city'] = $this->user_model->master_fun_get_tbl_val("test_cities", array("status" => '1'), array("name", "asc"));
        $data['branchlist'] = $this->user_model->master_fun_get_tbl_val("branch_master", array("status" => '1'), array("branch_name", "asc"));
        $data["new_query"] = array();

        foreach ($data["query"] as $kkey) {
            if (!empty($kkey["city"])) {
                $kkey["cityes"] = $this->user_model->get_val("SELECT GROUP_CONCAT(`name`) as name FROM `test_cities` WHERE `status`='1' AND id IN (" . $kkey["city"] . ") GROUP BY `status`");
            } else {
                $kkey["cityes"] = array();
            }
            if (!empty($kkey["branch"])) {
                $kkey["branches"] = $this->user_model->get_val("SELECT GROUP_CONCAT(`branch_name`) as name FROM `branch_master` WHERE `status`='1' AND id IN (" . $kkey["branch"] . ") GROUP BY `status`");
            } else {
                $kkey["branches"] = array();
            }
            $data["new_query"][] = $kkey;
        }
        //echo "<pre>"; print_r($data["new_query"]); die();
        $this->load->view('header');
        $this->load->view('nav', $data);
        $this->load->view('report_email_list', $data);
        $this->load->view('footer');
    }

    function add_user() {
        $this->user_model->master_fun_insert("daily_collection_report_persion", array("email" => $this->input->post("email"), "branch" => implode(",", $this->input->post("branch")), "city" => implode(",", $this->input->post("city"))));
        $this->session->set_flashdata("success", array("User successfully added."));
        redirect("Daily_dsr/user_list");
    }

    function delete_user($id) {
        $this->user_model->master_fun_update("daily_collection_report_persion", array("id", $id), array("status" => "0"));
        $this->session->set_flashdata("success", array("User successfully deleted."));
        redirect("Daily_dsr/user_list");
    }

    function test_details() {
        //$this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from('nishit@virtualheight.com', 'AirmedTest');
        $this->email->to('nishit@virtualheight.com');
        $this->email->subject('Email Test');
        $this->email->message('<b>Testing the email class.</b>');
        $this->email->send();
        echo $this->email->print_debugger();
    }

    /* END */
    /* PLM wise report */

    function send_plm_email() {
        $this->daily_export3("nishit@virtualheight.com", "10/10/2017", "2", "2");
    }

    /* function daily_export3($email = null, $start = null, $city = null, $branch = null) {
      $config['mailtype'] = 'html';
      $this->email->initialize($config);
      $this->load->helper("Email");
      $email_cnt = new Email;
      $this->load->dbutil();
      $this->load->helper('file');
      $this->load->helper('download');
      $date1 = explode("/", $start);
      $sd = $date1[2] . "-" . $date1[1] . "-" . $date1[0];
      $bd = date('Y-m-d', strtotime($sd . ' -1 day'));
      $back_date = $bd . " 00:00:00";
      $back_date1 = $bd . " 23:59:59";
      $start_date = $sd . " 00:00:00";
      $end_date = $sd . " 23:59:59";
      $delimiter = ",";
      $newline = "\r\n";
      $filename = "Daily_report_" . $start . ".csv";
      if ($this->session->flashdata("error")) {
      $data["error"] = $this->session->flashdata("error");
      }
      $data["login_data"] = logindata();
      if ($data["login_data"]['type'] == 2) {
      //   redirect('Admin/Telecaller');
      }

      $city_branch = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1), array("id", "asc"));
      $branch1 = array();
      //print_R($data["login_data"]['branch_fk']);
      foreach ($city_branch as $b) {
      $branch1[] = $b['id'];
      }
      if (!empty($branch)) {
      $branch1 = explode(",", $branch);
      } else {

      }
      //print_R($branch1); die();
      $data["dailyCollectionData"] = $this->getDailyCollectionDataPlm($start, ($branch1));
      $output = array();
      foreach ($data["dailyCollectionData"] as $branchData) {
      foreach ($branchData['dailyCollectionData'] as $am_br) {

      $crd_total = 0.0;
      $crd_due = 0.0;
      $crd_total = round($am_br["creditor_total"]);
      $crd_due = round($am_br["creditors_add"][0]["credit"]);
      $new_net += abs(($crd_total - $am_br["net"]) + $crd_due);

      $output[] = array(
      "branch_details" => $branchData["branch"],
      "Branch" => $branchData['branch_name'],
      "Received_By" => $am_br['username']['name'],
      "Gross_Amt" => ($am_br["gross_amount"] == "") ? "00" : round($am_br["gross_amount"]),
      "Discount" => ($am_br["discount"] == "") ? "00" : round($am_br["discount"]),
      "Net_Amt" => ($am_br["price"] == "") ? "00" : round($am_br["price"]),
      "cash" => ($am_br["cash_total"] == "") ? "00" : round($am_br["cash_total"]),
      "Cheque" => ($am_br["cheque_total"] == "") ? "00" : round($am_br["cheque_total"]),
      "Credit_Debit" => ($am_br["credit_total"] == "") ? "00" : round($am_br["credit_total"]),
      "Other_Credit" => ($am_br["other_total"] == "") ? "00" : round($am_br["other_total"]),
      "Same_Day" => ($am_br["same_day"] == "") ? "00" : round($am_br["same_day"]),
      "Back_Day" => ($am_br["back_day"] == "") ? "00" : round($am_br["back_day"]),
      "Creditor_Total" => ($crd_total == "") ? "00" : round($crd_total),
      "Creditor_Due" => ($crd_due == "") ? "00" : round($crd_due),
      "Net" => ($am_br["payable_amount"] == "") ? "00" : round($am_br["payable_amount"], 2),
      "all_job_count" => ($branchData["total_job_count"][0]["cnt"] == "") ? "0" : $branchData["total_job_count"][0]["cnt"],
      "revenue" => ($branchData["total_job_count"][0]["revenue"] == "") ? "0" : $branchData["total_job_count"][0]["revenue"],
      "due_amount" => ($branchData["total_job_count"][0]["due_amount"] == "") ? "0" : $branchData["total_job_count"][0]["due_amount"],
      "discount" => ($branchData["total_job_count"][0]["discount"] == "") ? "0" : $branchData["total_job_count"][0]["discount"]
      );
      }
      }
      // echo "<pre>";print_r($output); die();
      $message1 = '
      <div style="padding:0 4%;">';
      $message1 .= '<table id="customers" style=" border-collapse: collapse; width: 100%;">';
      $brnch = array();
      $q = 0;
      if ($_GET["debug"] == 1) {
      echo "<pre>";
      print_R($output);
      die();
      }
      foreach ($output as $value) {
      //echo "<pre>";print_r($value);die("OK123");
      if (!in_array($value["Branch"], $brnch)) {
      if ($q != 0) {
      $message1 .= '<tr style="background-color: #f2f2f2;">
      <td style="border: 1px solid #ddd; padding: 8px;">Today\'s Booking</td>
      <td style="border: 1px solid #ddd; padding: 8px;">' . $total_job . '</td>
      </tr>';
      $message1 .= '<tr >
      <td style="border: 1px solid #ddd; padding: 8px;">Today\'s Revenue</td>
      <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $revenue . '</td>
      </tr>';
      $message1 .= '<tr>
      <td style="border: 1px solid #ddd; padding: 8px;">Today\'s Due</td>
      <td style="border: 1px solid #ddd; padding: 8px;">Rs.' . $dueAmount . '</td>
      </tr>';
      //$message1 .= '<tr><td style="border: 1px solid #ddd; padding: 8px;" colspan="2"></td>';
      }
      if (empty($value["branch_details"]["parent_fk"])) {
      $message1 .= '<tr>
      <td style="border: 1px solid #ddd; padding: 8px;text-align:center;height:70;padding-top:50px;" colspan="2"><b>' . $value["Branch"] . '(PLM)</b></td>
      </tr>';
      }
      $message1 .= '<tr>
      <td style="border: 1px solid #ddd; padding: 8px;" colspan="2"><b>' . $value["Branch"] . '</b></td>
      </tr>';
      $gross = 0;
      $collected = 0;
      $sameday = 0;
      $backday = 0;
      $diposit_cash = 0;
      $creditor_receiv = 0;
      $discount = 0;
      $due = 0;
      $total_job = 0;
      $revenue = 0;
      $dueAmount = 0;
      $total_discount = 0;
      }
      $ttl = 0;
      $gross += $value["Gross_Amt"];
      $collected += $value["cash"] + $value["Creditor"] + $value["Other_Credit"] + $value["Credit_Debit"];
      $diposit_cash += $value["cash"];
      $sameday += $value["Same_Day"];
      $backday += $value["Back_Day"];
      $creditor_receiv += $value["Creditor_Total"];
      $discount += $value["Discount"];
      $due += $value["Net"];
      $ttl = $value["cash"] + $value["Creditor"] + $value["Other_Credit"] + $value["Credit_Debit"];
      $total_job = $value["all_job_count"];
      $revenue = $value["revenue"];
      $dueAmount = $value["due_amount"];
      $total_discount = $value["discount"];
      $brnch[] = $value["Branch"];
      $q++;
      if (count($output) == $q) {
      $message1 .= '<tr style="background-color: #f2f2f2;">
      <td style="border: 1px solid #ddd;padding: 8px;">Today\'s Booking</td>
      <td style="border: 1px solid #ddd;padding: 8px;">' . $total_job . '</td>
      </tr>';
      $message1 .= '<tr>
      <td style="border: 1px solid #ddd;padding: 8px;">Today\'s Revenue</td>
      <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $revenue . '</td>
      </tr>';
      $message1 .= '<tr>
      <td style="border: 1px solid #ddd;padding: 8px;">Today\'s Due</td>
      <td style="border: 1px solid #ddd;padding: 8px;">Rs.' . $dueAmount . '</td>
      </tr>';
      }
      }
      $message1 .= '</table></div>';
      $message1 = $email_cnt->get_design($message1);
      $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
      $this->email->to($email);
      $this->email->cc("nishit@virtualheight.com");
      $this->email->subject('Daily PLM Report');
      $this->email->message($message1);
      $this->email->send();
      $this->email->print_debugger();
      echo "Ok";
      }
      function getDailyCollectionDataPlm($start_date, $branch) {

      $branch = $this->job_model->get_val("SELECT id,branch_name,parent_fk FROM branch_master WHERE STATUS='1' AND (parent_fk IS NULL OR parent_fk='') ORDER BY branch_name ASC");

      $new_branch_array = array();

      foreach ($branch as $bkey) {
      $sub_branch = $this->job_model->get_val("SELECT id,branch_name,parent_fk FROM branch_master WHERE STATUS='1' AND parent_fk='" . $bkey["id"] . "' ORDER BY branch_name ASC");
      $new_branch_array[] = $bkey;
      foreach ($sub_branch as $sbkey) {
      $new_branch_array[] = $sbkey;
      }
      }
      $branch = $new_branch_array;
      $data['start_date'] = $start_date;
      $start_date = null;
      $end_date = null;
      if ($data['start_date'] != "") {
      $start_date = $data['start_date'];
      }
      $DailyCollectionData = array();
      foreach ($branch as $b) {
      $data['branch'] = $b;
      $data['userlistAddedBy'] = $this->job_model->get_val("SELECT  DISTINCT( job_master.`added_by`) FROM job_master WHERE !ISNULL(job_master.`added_by`) AND job_master.model_type=1 and (job_master.`added_by`)!='0' AND job_master.`branch_fk`='" . $data['branch']["id"] . "'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) ");

      $data['userlistCashAddedBy2'] = $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`added_by` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.added_by)    AND ISNULL(job_master_receiv_amount.`phlebo_fk`) AND jb.model_type=1 and  job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch']["id"] . "'");
      $data['userlistCashAddedByPhlebo'] = $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`phlebo_fk` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.`phlebo_fk`)    AND   jb.model_type=1 and  job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch']["id"] . "'");
      $usersaddedbyall = array_merge($data['userlistAddedBy'], $data['userlistCashAddedBy2']);
      $data['branch'] = $b;
      //echo "SELECT COUNT(id) AS cnt FROM job_master WHERE DATE_FORMAT(job_master.`date`, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('" . $data['start_date'] . " 00:00:00','%d/%m/%Y %H:%i:%s') AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('" . $data['start_date'] . " 23:59:59','%d/%m/%Y %H:%i:%s') AND `status`!='0' AND branch_fk='" . $data['branch'] . "'";die();
      $job_cnt = $sameday = $this->job_model->get_val("SELECT COUNT(id) AS cnt,SUM(price) AS revenue,SUM(`payable_amount`)AS due_amount,ROUND(SUM(`discount`*`price`/100))AS discount FROM job_master WHERE DATE_FORMAT(job_master.`date`, '%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('" . $data['start_date'] . " 00:00:00','%d/%m/%Y %H:%i:%s') AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('" . $data['start_date'] . " 23:59:59','%d/%m/%Y %H:%i:%s') AND `status`!='0' AND branch_fk='" . $data['branch']["id"] . "'");

      $addedBY = array();
      foreach ($usersaddedbyall as $o) {
      if (!empty($o['added_by'])) {
      $addedBY[] = $o['added_by'];
      }
      }
      $creditor_user = $this->job_model->get_val("SELECT  DISTINCT created_by FROM `creditors_balance` LEFT JOIN job_master ON job_master.id=`creditors_balance`.`job_id` WHERE job_master.`status` = '1' AND `creditors_balance`.`status` = '1'   AND DATE_FORMAT(creditors_balance.`created_date`, '%Y-%m-%d')  = STR_TO_DATE('" . $data['start_date'] . "','%d/%m/%Y') AND `credit`>0 ");
      foreach ($creditor_user as $oa) {
      $addedBY[] = $oa['created_by'];
      }
      $UsersData = array();
      if (count($addedBY) > 0) {
      $UsersData = $this->job_model->get_val("select * from admin_master where id in (" . implode($addedBY, ',') . ")");
      }
      $dataForPayment = array();
      $data['branchName'] = $this->user_model->master_fun_get_tbl_val("branch_master", array('status' => 1, "id" => $data['branch']["id"]), array("id", "asc"));
      $branch_name = $data['branchName'][0]["branch_name"];
      $data["online_payment"] = $this->user_model->get_val("SELECT  SUM(`job_master`.`price`) AS price,SUM(job_master.payable_amount) as payable_amount  FROM `job_master`  WHERE  job_master.model_type=1 and  `job_master`.`status`!='0'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) and  `job_master`.`branch_fk`='" . $data['branch']["id"] . "'  AND ISNULL( job_master.`phlebo_added`) AND ISNULL( job_master.`added_by`) ");
      if ($data["online_payment"][0]["price"] != "") {
      $u = array('username' => array("name" => "Online"), "type" => "online");
      $u["branch_name"] = $branch_name;
      $u['cash'] = 00;
      $u['gross_amount'] = $data["online_payment"][0]["price"];
      $u['discount'] = 00;
      $u['price'] = $data["online_payment"][0]["price"];
      $u['payable_amount'] = $data["online_payment"][0]["payable_amount"];
      $cash = 00;
      $u['cash_total'] = 00;
      $u['cash_ttt'] = $payments;
      $u['other_total'] = 00;
      $u['credit_total'] = 00;
      $u['creditor_total'] = 0.0;
      $u['cheque_total'] = 00;
      $u['same_day'] = 00; //($sameday[0]['price']!="")?$sameday[0]['price']:00;
      $u['back_day'] = 00; ///($backday[0]['price']!="")?$backday[0]['price']:00;;
      $u['net'] = 00;
      // print_r($data["online_payment"]);die();
      $dataForPayment[] = $u;
      }
      foreach ($UsersData as $user) {
      $u = array('username' => $user, "type" => "user");
      $u["branch_name"] = $branch_name;
      $payments = $this->job_model->get_val("SELECT  c.payment_type , SUM(c.amount)  AS amount FROM   job_master_receiv_amount c   LEFT JOIN job_master jb     ON jb.id = c.job_fk WHERE     c.added_by = '" . $user['id'] . "'   AND DATE_FORMAT(c.`createddate`, '%Y-%m-%d') =STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND c.status = '1'  and jb.model_type=1    AND jb.branch_fk = " . $data['branch']["id"] . "   GROUP BY  c.payment_type ");
      $u['JOBS'] = $this->job_model->get_val("SELECT SUM(job_master.`price`) AS price,SUM(job_master.`price`*job_master.`discount`/100) AS discount,SUM(job_master.payable_amount) as payable_amount FROM job_master WHERE
      job_master.model_type=1 and  job_master.branch_fk =" . $data['branch']["id"] . " and  job_master.`added_by` =  '" . $user['id'] . "' AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d')=STR_TO_DATE('" . $data['start_date'] . "',    '%d/%m/%Y')");
      $u["creditors_add"] = $this->user_model->get_val("SELECT SUM(debit) AS debit,SUM(credit) AS credit FROM `creditors_balance` WHERE `status`='1' AND DATE_FORMAT(created_date, '%Y-%m-%d') = STR_TO_DATE('" . $data['start_date'] . "', '%d/%m/%Y') AND `created_by`='" . $user['id'] . "'");
      $u['samedaytest'] = $sameday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s left JOIN job_master jbs   ON jbs.id = s.job_fk WHERE  jbs.model_type=1 AND jbs.`status`!='0' and  s.added_by =  '" . $user['id'] . "' and s.payment_type!='CREDITORS'  AND jbs.branch_fk =" . $data['branch']["id"] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND DATE_FORMAT(jbs.date  , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND s.status = '1' ");
      $u['backdaytest'] = $backday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE jbs.model_type=1 AND jbs.`status`!='0' and s.added_by =  '" . $user['id'] . "'   AND jbs.branch_fk =" . $data['branch']["id"] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND jbs.date   < STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND s.status = '1' ");
      $u['gross_amount'] = $u['JOBS'][0]["price"];
      $u['discount'] = $u['JOBS'][0]["discount"];
      $u['price'] = $u['JOBS'][0]["price"] - $u['JOBS'][0]["discount"];
      $u['payable_amount'] = $u['JOBS'][0]["payable_amount"];
      $cash = 00;
      $u['cash_total'] = 0.0;
      $u['cash_ttt'] = $payments;
      $u['other_total'] = 0.0;
      $u['credit_total'] = 0.0;
      $u['creditor_total'] = 0.0;
      $u['cheque_total'] = 0.0;
      $u['same_day'] = ($sameday[0]['price'] != "") ? $sameday[0]['price'] : 0.0;
      $u['back_day'] = ($backday[0]['price'] != "") ? $backday[0]['price'] : 0.0;
      $u['net'] = round($u['price'] - $u['same_day'], 2);
      $u['net'] = ((int) $u['net'] < 0) ? 0.0 : $u['net'];
      foreach ($payments as $p) {
      if ($p['payment_type'] == "CASH") {
      $u['cash_total'] = $p['amount'];
      } else
      if ($p['payment_type'] == "CHEQUE") {
      $u['cheque_total'] += $p['amount'];
      } else if (in_array($p['payment_type'], array("CREDIT CARD", "CREDIT CARD swiped thru ICICI", 'WALLET CREDIT CARD swiped thru MSWIP', 'DEBIT CARD swiped thru ICICI', 'DEBIT CARD swiped thru MSWIP', 'Swipe thru HDFC', 'Swipe thru AXIS', 'DEBIT CARD'))) {
      $u['credit_total'] += $p['amount'];
      } else if (in_array($p['payment_type'], array("CREDITORS"))) {
      $u['creditor_total'] += $p['amount'];
      } else {
      $u['other_total'] += $p['amount'];
      }
      }


      $dataForPayment[] = $u;
      }

      if (!empty($data['branch']["id"])) {
      $phleboid = $this->job_model->get_val("SELECT  DISTINCT( job_master.`phlebo_added`) FROM job_master WHERE !ISNULL(job_master.`phlebo_added`) AND (job_master.`phlebo_added`)!='0' AND job_master.model_type=1 and job_master.`branch_fk`='" . $data['branch']["id"] . "'  AND DATE_FORMAT(job_master.`date`, '%Y-%m-%d') = STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )");
      $phleboid += $this->job_model->get_val("SELECT   DISTINCT(job_master_receiv_amount.`phlebo_fk` ) FROM  `job_master_receiv_amount`   LEFT JOIN job_master jb     ON jb.id = job_master_receiv_amount.job_fk  WHERE ! ISNULL(job_master_receiv_amount.phlebo_fk)   AND jb.model_type=1 and   job_master_receiv_amount.status != 0 AND DATE_FORMAT( job_master_receiv_amount.`createddate`,'%d/%m/%Y')= '" . $data['start_date'] . "' and jb.branch_fk='" . $data['branch']["id"] . "'");
      $addedBY = array();
      foreach ($data['userlistCashAddedByPhlebo'] as $o) {
      if ($o['phlebo_fk'] != "") {
      $addedBY[] = $o['phlebo_fk'];
      }
      }
      }
      $UsersData = array();
      if (count($addedBY) > 0) {
      $UsersData = $this->job_model->get_val("SELECT * FROM  `phlebo_master`  where id in (" . implode($addedBY, ',') . ")");
      }
      foreach ($UsersData as $user) {
      $u = array('username' => $user, "type" => "phlebo");
      $u["branch_name"] = $branch_name;
      $u['cash'] = $this->job_model->get_val("SELECT SUM(c.amount) as 'CASH' FROM job_master_receiv_amount c LEFT JOIN job_master jb ON jb.id = c.job_fk WHERE (c.phlebo_fk='" . $user['id'] . "') AND  jb.model_type=1 and  c.payment_type='CASH' AND DATE_FORMAT(c.`createddate`,'%Y-%m-%d')= STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  ) AND c.status='1' AND jb.branch_fk=" . $data['branch']["id"] . "");
      //$u["cash"]=($u["cash"][0]['CASH']=="")?"00":$u["cash"][0]['CASH'];
      $payments = $this->job_model->get_val("SELECT  c.payment_type , SUM(c.amount)  AS amount FROM   job_master_receiv_amount c   LEFT JOIN job_master jb     ON jb.id = c.job_fk WHERE     c.phlebo_fk = '" . $user['id'] . "'   AND DATE_FORMAT(c.`createddate`, '%Y-%m-%d') =STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND c.status = '1'  and jb.model_type=1  AND jb.branch_fk = " . $data['branch']["id"] . "   GROUP BY  c.payment_type ");
      //echo $this->db->last_query()."<br>";
      $u['JOBS'] = $this->job_model->get_val("SELECT SUM(job_master.`price`) AS price,SUM(job_master.`price`*job_master.`discount`/100) AS discount,SUM(job_master.payable_amount) as payable_amount FROM job_master WHERE
      job_master.branch_fk =" . $data['branch']["id"] . " and job_master.model_type=1 and   job_master.`phlebo_added` =  '" . $user['id'] . "' AND DATE_FORMAT(job_master.`date`,'%Y-%m-%d')=STR_TO_DATE('" . $data['start_date'] . "',    '%d/%m/%Y')");
      //echo $this->db->last_query()."<br>";
      $u['samedaytest'] = $sameday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE jbs.model_type=1 and  s.phlebo_fk =  '" . $user['id'] . "'  and s.payment_type!='CREDITORS' AND jbs.branch_fk =" . $data['branch']["id"] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND DATE_FORMAT(jbs.date  , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND s.status = '1' ");
      $u['backdaytest'] = $backday = $this->job_model->get_val("SELECT   SUM(s.amount)  AS price FROM  job_master_receiv_amount s JOIN job_master jbs   ON jbs.id = s.job_fk WHERE  jbs.model_type=1 and  s.phlebo_fk =  '" . $user['id'] . "'   AND jbs.branch_fk =" . $data['branch']["id"] . "  AND DATE_FORMAT( s.createddate , '%d/%m/%Y') = '" . $data['start_date'] . "'   AND jbs.date   < STR_TO_DATE(    '" . $data['start_date'] . "',    '%d/%m/%Y'  )   AND s.status = '1' ");
      $u['gross_amount'] = $u['JOBS'][0]["price"];
      $u['discount'] = $u['JOBS'][0]["discount"];
      $u['price'] = $u['JOBS'][0]["price"] - $u['JOBS'][0]["discount"];
      $u['payable_amount'] = $u['JOBS'][0]["payable_amount"];
      $cash = 00;
      $u['cash_total'] = 0.0;
      $u['other_total'] = 0.0;
      $u['credit_total'] = 0.0;
      $u['creditor_total'] = 0.0;
      $u['cheque_total'] = 0.0;
      $u['same_day'] = ($sameday[0]['price'] != "") ? $sameday[0]['price'] : 0.0;
      $u['back_day'] = ($backday[0]['price'] != "") ? $backday[0]['price'] : 0.0;
      //$u['net']=$u['price']-$u['same_day'];
      $u['net'] = round($u['price'] - $u['same_day'], 2);
      $u['net'] = ((int) $u['net'] < 0) ? 0.0 : $u['net'];
      foreach ($payments as $p) {
      if ($p['payment_type'] == "CASH") {
      $u['cash_total'] = $p['amount'];
      } else
      if ($p['payment_type'] == "CHEQUE") {
      $u['cheque_total'] += $p['amount'];
      } else if (in_array($u['payment_type'], array('CREDIT CARD', 'CREDIT CARD swiped thru ICICI', 'WALLET CREDIT CARD swiped thru MSWIP', 'DEBIT CARD swiped thru ICICI', 'DEBIT CARD swiped thru MSWIP', 'Swipe thru HDFC', 'Swipe thru AXIS', 'DEBIT CARD'))) {
      //(cr.payment_type='CREDIT CARD' or cr.payment_type='CREDIT CARD swiped thru ICICI' or cr.payment_type='CREDIT CARD swiped thru MSWIP' or cr.payment_type='DEBIT CARD swiped thru ICICI' or cr.payment_type='DEBIT CARD swiped thru MSWIP' or cr.payment_type='Swipe thru HDFC' or cr.payment_type='Swipe thru AXIS' or cr.payment_type='DEBIT CARD')
      $u['credit_total'] += $p['amount'];
      } else if (in_array($p['payment_type'], array("CREDITORS"))) {
      $u['creditor_total'] += $p['amount'];
      } else {
      $u['other_total'] += $p['amount'];
      }
      }
      $dataForPayment[] = $u;
      }
      $BrnachDailyCollectionData[] = array("branch" => $b, "branch_name" => $branch_name, "total_job_count" => $job_cnt, "dailyCollectionData" => $dataForPayment);
      }
      $dataforcollection = array();
      //echo "<prE>"; print_r($BrnachDailyCollectionData); die();
      return $BrnachDailyCollectionData;
      } */

    /* Nishit new code start */

    function daily_export3($email = null, $start = null, $city = null, $branch = null) {
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->load->helper("Email");
        $email_cnt = new Email;
        $this->load->dbutil();
        $this->load->helper('file');
        //print_R($branch1); die();
        $message1 = $this->getDailyCollectionDataPlm($start, $branch1);
        //$message1 = $email_cnt->get_design($message1);
        $this->email->from($this->config->item('admin_booking_email'), "AirmedLabs");
        //$this->email->to($email); 
        $this->email->to(array("satyabrata.dutta@airmedlabs.com", "amit.gupta@airmedpathlabs.com", "piyush.gupta@airmedpathlabs.com"));
        //$this->email->to(array("nishit@virtualheight.com", "kana@virtualheight.com","nishit.patel@airmedlabs.com"));
        $this->email->subject('Daily PLM revenue report');
        $this->email->message($message1);
        $this->email->send();
        $this->email->print_debugger();
        echo $message1;
    }

    function getDailyCollectionDataPlm($start_date, $branch) {
        $test_city_name = $this->job_model->get_val("SELECT * FROM test_cities WHERE STATUS='1' ORDER BY name ASC");
        $new_branch_array = array();
        $date = date("Y-m-d");
        //$date = "2017-12-13";
        $newDate = date("d-m-Y", strtotime($date));
        foreach ($test_city_name as $tkey) {
            $branch = $this->job_model->get_val("SELECT * FROM branch_master WHERE STATUS='1' AND (parent_fk IS NULL OR parent_fk='') and city='" . $tkey["id"] . "' ORDER BY branch_name ASC");
            $final_branch_data = array();
            $plm_branch_total = 0;
            $sub_branch_total_final = 0;
            $sub_client_total_final = 0;

            foreach ($branch as $bkey) {
                $total = $this->get_b2c_branch_revenue($bkey["id"], $date);
                //$b2b_client_list_total_final += $total;
                $sub_branch = $this->job_model->get_val("SELECT * FROM branch_master WHERE STATUS='1' and  (parent_fk='" . $bkey["id"] . "' or id='" . $bkey["id"] . "') ORDER BY branch_name ASC");
                $new_data_array = array();
                $sub_branch_total = 0;
                $b2b_client_list_total_final = 0;
                foreach ($sub_branch as $s_key) {
                    $sub_b2b_client1 = $this->job_model->get_val("SELECT 
  `b2b_labgroup`.*,
  `collect_from`.`name` AS lab_name,
  CONCAT(`sales_user_master`.`first_name`,' ',`sales_user_master`.`last_name`) AS sales_person
FROM
  `b2b_labgroup` 
  INNER JOIN `collect_from` 
    ON `b2b_labgroup`.`labid` = `collect_from`.`id` 
    LEFT JOIN `sales_user_master` ON `sales_user_master`.`id`= `collect_from`.`sales_fk` 
WHERE `collect_from`.`status` = '1' 
  AND `b2b_labgroup`.`status` = '1' and `b2b_labgroup`.`branch_fk` = '" . $s_key["id"] . "'");
                    $b2b_client_list_total = 0;
                    $sub_b2b_client_new = array();
                    foreach ($sub_b2b_client1 as $key_total) {
                        $b2b_client_list_total += $this->get_b2b_branch_revenue($key_total["labid"], $date);
                        $key_total["total"] = $this->get_b2b_branch_revenue($key_total["labid"], $date);
                        $sub_b2b_client_new[] = $key_total;
                    }
                    $s_key["b2b_client_list"] = $sub_b2b_client_new;
                    $s_key["b2b_client_list_total"] = $b2b_client_list_total;
                    $b2b_client_list_total_final += $b2b_client_list_total;
                    $sub_branch_total += $this->get_b2c_branch_revenue($s_key["id"], $date);
                    $sub_branch_total1 = $this->get_b2c_branch_revenue($s_key["id"], $date);
                    $s_key["total"] = $this->get_b2c_branch_revenue($s_key["id"], $date);
                    $s_key["branch_total"] = $sub_branch_total1;
                    $new_data_array[] = $s_key;
                }

                $sub_b2b_client = $this->job_model->get_val("SELECT 
  `b2b_labgroup`.*,
  `collect_from`.`name` AS lab_name,
  CONCAT(`sales_user_master`.`first_name`,' ',`sales_user_master`.`last_name`) AS sales_person
FROM
  `b2b_labgroup` 
  INNER JOIN `collect_from` 
    ON `b2b_labgroup`.`labid` = `collect_from`.`id` 
    LEFT JOIN `sales_user_master` ON `sales_user_master`.`id`= `collect_from`.`sales_fk` 
WHERE `collect_from`.`status` = '1' 
  AND `b2b_labgroup`.`status` = '1' and `b2b_labgroup`.`branch_fk` = '" . $bkey["id"] . "'");
                $sub_client_total = 0;
                $sub_b2b_client_new = array();

                foreach ($sub_b2b_client as $sbc_key) {
                    //print_R($sbc_key); die();
                    $sub_client_total += $this->get_b2b_branch_revenue($sbc_key["labid"], $date);
                    $sbc_key["total"] = $this->get_b2b_branch_revenue($sbc_key["labid"], $date);
                    $sub_b2b_client_new[] = $sbc_key;
                }
                $btotal = $this->get_b2c_branch_revenue($bkey["id"], $date);
                $bkey["sub_branch"] = $new_data_array;
                $bkey["sub_branch_total"] = $sub_branch_total;
                $sub_branch_total_final += $sub_branch_total;
                $bkey["b2b_client_list_total_final"] = $b2b_client_list_total_final;
                /* $bkey["sub_client"] = $sub_b2b_client_new; */
                $bkey["sub_client_total"] = $sub_client_total;
                $sub_client_total_final += $sub_client_total;
                $plm_branch_total += $this->get_b2c_branch_revenue($sbc_key["id"], $date);
                $final_branch_data[] = $bkey;
            }

            $tkey["plm_branch"] = $final_branch_data;
            $tkey["sub_branch_total_final"] = $sub_branch_total_final;
            $tkey["sub_client_total_final"] = $sub_client_total_final;
            $tkey["plm_branch_total"] = $plm_branch_total;
            $new_branch_array[] = $tkey;
        }
        /* echo "<pre>";
          print_r($new_branch_array);
          die(); */

        $funll_final_array = array();
        $design = "";
        foreach ($new_branch_array as $ff_key) {
            $one = array();
            $one["city"] = $ff_key["name"];
            $one["code"] = $ff_key["code"];
            $one["branch_b2c_total"] = $ff_key["sub_branch_total_final"];
            $one["branch_b2b_total"] = $ff_key["sub_client_total_final"];
            $two = array();
            foreach ($ff_key["plm_branch"] as $ff_plm_key) {
                $five = array();
                $three = array();
                $six = array();
                $b2b_final_total = 0;
                foreach ($ff_plm_key["sub_branch"] as $ff_sub_branch_key) {
                    $six1 = array();
                    foreach ($ff_sub_branch_key["b2b_client_list"] as $ff_sub_branch_key1) {
                        //print_r($ff_sub_branch_key); die();
                        $four1 = array();
                        $four1["client_name"] = $ff_sub_branch_key1["lab_name"];
                        $four1["b2b_total"] = $ff_sub_branch_key1["total"];
                        $four1["sales_person"] = $ff_sub_branch_key1["sales_person"];
                        $six1[] = $four1;
                    }
                    $four = array();
                    $four["plm_code"] = $ff_sub_branch_key["branch_code"];
                    $four["plm_name"] = $ff_sub_branch_key["branch_name"];
                    $four["b2c_total"] = $ff_sub_branch_key["branch_total"];
                    $four["b2b_total"] = $ff_sub_branch_key["b2b_client_list_total"];
                    $four["b2b_client"] = $six1;
                    $five[] = $four;
                    $b2b_final_total += $ff_sub_branch_key["b2b_client_list_total"];
                }
                $three["plm_code"] = $ff_plm_key["branch_code"];
                $three["plm_name"] = $ff_plm_key["branch_name"];
                $three["b2c_total"] = $ff_plm_key["sub_branch_total"];
                //$three["b2b_total2"] = $ff_plm_key["sub_client_total_final"];
                $three["b2b_total"] = $b2b_final_total;
                $three["sub_branch"] = $five;
                $two[] = $three;
            }
            $one["plm"] = $two;
            $funll_final_array[] = $one;
        }
        $html = '';
        $html .= '<style>
            /*tablle, tr, th, td{width:500px;}*/
        </style>';
        //echo "<pre>"; print_r($funll_final_array); die();
        foreach ($funll_final_array as $cityRow) {
            $city_final_total = $cityRow["branch_b2c_total"] + $cityRow["branch_b2b_total"];
            //if ($city_final_total > 0) {
            $cty_ttl = $cityRow["branch_b2c_total"] + $cityRow["branch_b2b_total"];
            if ($cty_ttl > 0) {
                $html .= '<br><h1 style="margin-bottom:5px;">' . $cityRow['city'] . '(Rs.' . $cty_ttl . ')<small>(' . $newDate . ')</small></h1>';
                $html .= '<ul>';
                foreach ($cityRow['plm'] as $plmRow) {
                    if (1 > 0) {

                        $html .= '';
                        $html .= '<br><li><b>' . $plmRow['plm_name'] . "</b>";
                        if ($plmRow['b2c_total'] > 0 || $plmRow['b2b_total'] > 0) {
                            $html .= "(Rs.";
                            $html .= $plmRow['b2c_total'] + $plmRow['b2b_total'] . ")";
                        }

                        $html .= '';
                        $ncnt = 0;
                        foreach ($plmRow['sub_branch'] as $centorRow) {
                            $new_html = "";
                            if ($ncnt == 0) {
                                $new_html .= '<ul>';
                            }
                            $bsbtotal = 0;
                            foreach ($centorRow['b2b_client'] as $bkey) {
                                $bsbtotal += $bkey['b2b_total'];
                            }
                            if ($bsbtotal > 0 || $centorRow['b2c_total'] > 0) {
                                $new_html .= '<li>' . $centorRow['plm_name'];
                                if ($centorRow['b2c_total'] > 0) {
                                    $new_html .= " (Rs." . $centorRow['b2c_total'] . ")";
                                } else {
                                    $new_html .= " (Rs.0)";
                                }
                                $new_html .= '';
                                $cnt = 0;
                                $b2b_sub_total = 0;
                                foreach ($centorRow['b2b_client'] as $bkey) {
                                    if ($bkey['b2b_total'] > 0) {
                                        if ($cnt == 0) {

                                            $new_html .= "<ul>";
                                        }
                                        $new_html .= '';
                                        $new_html .= '<li>';
                                        $new_html .= $bkey['client_name'] . "<small> (" . $bkey["sales_person"] . ")</small> " . "(Rs." . $bkey['b2b_total'] . ")</li>";
                                        $b2b_sub_total += $bkey['b2b_total'];
                                        $cnt++;
                                    }
                                }
                                if ($cnt > 0) {

                                    $new_html .= "</ul>";
                                }
                                $new_html .= '</li>';
                                /* $html .= '</tr>'; */
                                $skfinal_total = $centorRow['b2c_total'] + $b2b_sub_total;
                                if ($skfinal_total > 0) {
                                    $html .= $new_html;
                                    $ncnt++;
                                }
                            }
                        }
                        if ($ncnt > 0) {
                            $html .= '</ul>';
                        }
                    }
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        }
        return $html;
    }

    function getDailyCollectionDataPlm_demo($start_date, $branch) {
        $test_city_name = $this->job_model->get_val("SELECT * FROM test_cities WHERE STATUS='1' ORDER BY name ASC");
        $new_branch_array = array();
        $date = date("Y-m-d");
        $date = "2018-02-08";
        $newDate = date("d-m-Y", strtotime($date));
        foreach ($test_city_name as $tkey) {
            $branch = $this->job_model->get_val("SELECT * FROM branch_master WHERE STATUS='1' AND (parent_fk IS NULL OR parent_fk='') and city='" . $tkey["id"] . "' ORDER BY branch_name ASC");
            $final_branch_data = array();
            $plm_branch_total = 0;
            $sub_branch_total_final = 0;
            $sub_client_total_final = 0;

            foreach ($branch as $bkey) {
                $total = $this->get_b2c_branch_revenue($bkey["id"], $date);
                //$b2b_client_list_total_final += $total;
                $sub_branch = $this->job_model->get_val("SELECT * FROM branch_master WHERE STATUS='1' and  (parent_fk='" . $bkey["id"] . "' or id='" . $bkey["id"] . "') ORDER BY branch_name ASC");
                $new_data_array = array();
                $sub_branch_total = 0;
                $b2b_client_list_total_final = 0;
                foreach ($sub_branch as $s_key) {
                    $sub_b2b_client1 = $this->job_model->get_val("SELECT 
  `b2b_labgroup`.*,
  `collect_from`.`name` AS lab_name,
  CONCAT(`sales_user_master`.`first_name`,' ',`sales_user_master`.`last_name`) AS sales_person
FROM
  `b2b_labgroup` 
  INNER JOIN `collect_from` 
    ON `b2b_labgroup`.`labid` = `collect_from`.`id` 
    LEFT JOIN `sales_user_master` ON `sales_user_master`.`id`= `collect_from`.`sales_fk` 
WHERE `collect_from`.`status` = '1' 
  AND `b2b_labgroup`.`status` = '1' and `b2b_labgroup`.`branch_fk` = '" . $s_key["id"] . "'");
                    $b2b_client_list_total = 0;
                    $sub_b2b_client_new = array();
                    foreach ($sub_b2b_client1 as $key_total) {
                        $b2b_client_list_total += $this->get_b2b_branch_revenue($key_total["labid"], $date);
                        $key_total["total"] = $this->get_b2b_branch_revenue($key_total["labid"], $date);
                        $sub_b2b_client_new[] = $key_total;
                    }
                    $s_key["b2b_client_list"] = $sub_b2b_client_new;
                    $s_key["b2b_client_list_total"] = $b2b_client_list_total;
                    $b2b_client_list_total_final += $b2b_client_list_total;
                    $sub_branch_total += $this->get_b2c_branch_revenue($s_key["id"], $date);
                    $sub_branch_total1 = $this->get_b2c_branch_revenue($s_key["id"], $date);
                    $s_key["total"] = $this->get_b2c_branch_revenue($s_key["id"], $date);
                    $s_key["branch_total"] = $sub_branch_total1;
                    $new_data_array[] = $s_key;
                }

                $sub_b2b_client = $this->job_model->get_val("SELECT 
  `b2b_labgroup`.*,
  `collect_from`.`name` AS lab_name,
  CONCAT(`sales_user_master`.`first_name`,' ',`sales_user_master`.`last_name`) AS sales_person
FROM
  `b2b_labgroup` 
  INNER JOIN `collect_from` 
    ON `b2b_labgroup`.`labid` = `collect_from`.`id` 
    LEFT JOIN `sales_user_master` ON `sales_user_master`.`id`= `collect_from`.`sales_fk` 
WHERE `collect_from`.`status` = '1' 
  AND `b2b_labgroup`.`status` = '1' and `b2b_labgroup`.`branch_fk` = '" . $bkey["id"] . "'");
                $sub_client_total = 0;
                $sub_b2b_client_new = array();

                foreach ($sub_b2b_client as $sbc_key) {
                    //print_R($sbc_key); die();
                    $sub_client_total += $this->get_b2b_branch_revenue($sbc_key["labid"], $date);
                    $sbc_key["total"] = $this->get_b2b_branch_revenue($sbc_key["labid"], $date);
                    $sub_b2b_client_new[] = $sbc_key;
                }
                $btotal = $this->get_b2c_branch_revenue($bkey["id"], $date);
                $bkey["sub_branch"] = $new_data_array;
                $bkey["sub_branch_total"] = $sub_branch_total;
                $sub_branch_total_final += $sub_branch_total;
                $bkey["b2b_client_list_total_final"] = $b2b_client_list_total_final;
                /* $bkey["sub_client"] = $sub_b2b_client_new; */
                $bkey["sub_client_total"] = $sub_client_total;
                $sub_client_total_final += $sub_client_total;
                $plm_branch_total += $this->get_b2c_branch_revenue($sbc_key["id"], $date);
                $final_branch_data[] = $bkey;
            }

            $tkey["plm_branch"] = $final_branch_data;
            $tkey["sub_branch_total_final"] = $sub_branch_total_final;
            $tkey["sub_client_total_final"] = $sub_client_total_final;
            $tkey["plm_branch_total"] = $plm_branch_total;
            $new_branch_array[] = $tkey;
        }
        /* echo "<pre>";
          print_r($new_branch_array);
          die(); */

        $funll_final_array = array();
        $design = "";
        foreach ($new_branch_array as $ff_key) {
            $one = array();
            $one["city"] = $ff_key["name"];
            $one["code"] = $ff_key["code"];
            $one["branch_b2c_total"] = $ff_key["sub_branch_total_final"];
            $one["branch_b2b_total"] = $ff_key["sub_client_total_final"];
            $two = array();
            foreach ($ff_key["plm_branch"] as $ff_plm_key) {
                $five = array();
                $three = array();
                $six = array();
                $b2b_final_total = 0;
                foreach ($ff_plm_key["sub_branch"] as $ff_sub_branch_key) {
                    $six1 = array();
                    foreach ($ff_sub_branch_key["b2b_client_list"] as $ff_sub_branch_key1) {
                        //print_r($ff_sub_branch_key); die();
                        $four1 = array();
                        $four1["client_name"] = $ff_sub_branch_key1["lab_name"];
                        $four1["b2b_total"] = $ff_sub_branch_key1["total"];
                        $four1["sales_person"] = $ff_sub_branch_key1["sales_person"];
                        $six1[] = $four1;
                    }
                    $four = array();
                    $four["plm_code"] = $ff_sub_branch_key["branch_code"];
                    $four["plm_name"] = $ff_sub_branch_key["branch_name"];
                    $four["b2c_total"] = $ff_sub_branch_key["branch_total"];
                    $four["b2b_total"] = $ff_sub_branch_key["b2b_client_list_total"];
                    $four["b2b_client"] = $six1;
                    $five[] = $four;
                    $b2b_final_total += $ff_sub_branch_key["b2b_client_list_total"];
                }
                $three["plm_code"] = $ff_plm_key["branch_code"];
                $three["plm_name"] = $ff_plm_key["branch_name"];
                $three["b2c_total"] = $ff_plm_key["sub_branch_total"];
                //$three["b2b_total2"] = $ff_plm_key["sub_client_total_final"];
                $three["b2b_total"] = $b2b_final_total;
                $three["sub_branch"] = $five;
                $two[] = $three;
            }
            $one["plm"] = $two;
            $funll_final_array[] = $one;
        }
        $html = '';
        $html .= '<style>
            /*tablle, tr, th, td{width:500px;}*/
        </style>';
        //echo "<pre>"; print_r($funll_final_array); die();
        foreach ($funll_final_array as $cityRow) {
            $city_final_total = $cityRow["branch_b2c_total"] + $cityRow["branch_b2b_total"];
            //if ($city_final_total > 0) {
            $cty_ttl = $cityRow["branch_b2c_total"] + $cityRow["branch_b2b_total"];
            if ($cty_ttl > 0) {
                $html .= '<br><h1 style="margin-bottom:5px;">' . $cityRow['city'] . '(Rs.' . $cty_ttl . ')<small>(' . $newDate . ')</small></h1>';
                $html .= '<ul>';
                foreach ($cityRow['plm'] as $plmRow) {
                    if (1 > 0) {

                        $html .= '';
                        $html .= '<br><li><b>' . $plmRow['plm_name'] . "</b>";
                        if ($plmRow['b2c_total'] > 0 || $plmRow['b2b_total'] > 0) {
                            $html .= "(Rs.";
                            $html .= $plmRow['b2c_total'] + $plmRow['b2b_total'] . ")";
                        }

                        $html .= '';
                        $ncnt = 0;
                        foreach ($plmRow['sub_branch'] as $centorRow) {
                            $new_html = "";
                            if ($ncnt == 0) {
                                $new_html .= '<ul>';
                            }
                            $bsbtotal = 0;
                            foreach ($centorRow['b2b_client'] as $bkey) {
                                $bsbtotal += $bkey['b2b_total'];
                            }
                            if ($bsbtotal > 0 || $centorRow['b2c_total'] > 0) {
                                $new_html .= '<li>' . $centorRow['plm_name'];
                                if ($centorRow['b2c_total'] > 0) {
                                    $new_html .= " (Rs." . $centorRow['b2c_total'] . ")";
                                } else {
                                    $new_html .= " (Rs.0)";
                                }
                                $new_html .= '';
                                $cnt = 0;
                                $b2b_sub_total = 0;
                                foreach ($centorRow['b2b_client'] as $bkey) {
                                    if ($bkey['b2b_total'] > 0) {
                                        if ($cnt == 0) {

                                            $new_html .= "<ul>";
                                        }
                                        $new_html .= '';
                                        $new_html .= '<li>';
                                        $new_html .= $bkey['client_name'] . "<small> (" . $bkey["sales_person"] . ")</small> " . "(Rs." . $bkey['b2b_total'] . ")</li>";
                                        $b2b_sub_total += $bkey['b2b_total'];
                                        $cnt++;
                                    }
                                }
                                if ($cnt > 0) {

                                    $new_html .= "</ul>";
                                }
                                $new_html .= '</li>';
                                /* $html .= '</tr>'; */
                                $skfinal_total = $centorRow['b2c_total'] + $b2b_sub_total;
                                if ($skfinal_total > 0) {
                                    $html .= $new_html;
                                    $ncnt++;
                                }
                            }
                        }
                        if ($ncnt > 0) {
                            $html .= '</ul>';
                        }
                    }
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        }
        echo $html;
        die();
    }

    function get_b2c_branch_revenue($bid = null, $date = null) {
        $revenue = 0;
        if ($bid != null && $date != null) {
            $sub_b2b_client = $this->job_model->get_val("SELECT 
  SUM(price) AS revenue 
FROM
  job_master 
WHERE `status` != '0' 
  AND branch_fk = '" . $bid . "' 
  AND `date` >= '" . $date . " 00:00:00' 
  AND `date` <= '" . $date . " 23:59:59' and model_type='1'");
            $revenue = $sub_b2b_client[0]["revenue"];
        }
        return $revenue;
    }

    function get_b2b_branch_revenue($bid = null, $date = null) {
        $revenue = 0;
        if ($bid != null && $date != null) {
            $sub_b2b_client = $this->job_model->get_val("SELECT 
      SUM(`sample_job_master`.`price`) AS revenue  
    FROM
      `logistic_log` 
      INNER JOIN `sample_job_master` 
        ON `sample_job_master`.`barcode_fk` = `logistic_log`.`id` 
    WHERE `logistic_log`.`collect_from` = '" . $bid . "' 
      AND `logistic_log`.`scan_date` >= '" . $date . " 00:00:00' 
      AND `logistic_log`.`scan_date` <= '" . $date . " 23:59:59' AND `sample_job_master`.`status`='1' AND `logistic_log`.`status`='1'");
            $revenue = $sub_b2b_client[0]["revenue"];
        }
        return $revenue;
    }

    /* END */
    /* END */
}
