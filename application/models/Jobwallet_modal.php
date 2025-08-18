<?php

class Jobwallet_modal extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function getUser($user_id) {
        $query = $this->db->get_where("admin_master", array("id" => $user_id, "status" => "1"));
        return $query->row();
    }
    function getwallet_num($reg_id=null,$ord_id=null,$customer_name=null,$start_date=null,$end_date=null) {
            
        //$qry = "SELECT j.id,j.`order_id`,j.`date`,j.`price`,c.`full_name` FROM `job_master` j LEFT JOIN `customer_master` c ON c.`id`=j.`cust_fk` WHERE j.status !='0'  AND j.`portal` IN ('android','ios')";
        $temp = "";
             
                if($reg_id != ''){
                     $temp .= " AND j.id ='$reg_id' ";
                   
                }
             if($ord_id != ''){
                  $temp .= " AND j.order_id ='$ord_id' ";
                    
                }
                 if($customer_name != ''){
                   $temp .= " AND c.full_name like '%$customer_name%'";
                    
                }
                if($start_date != ''){
					 $old_date  = explode('/',$start_date);
               $o_date = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
               $new_date = $o_date ." 00:00:00";
			   
                    $temp .= " AND j.date >='$new_date'";
                   
                }
                 if($end_date != ''){
					  $old_end_date  = explode('/',$end_date);
               $o_end_date = $old_end_date[2].'-'.$old_end_date[1].'-'.$old_end_date[0];
               $end_new_date = $o_end_date ." 23:59:59";
			   
                     $temp .= " AND j.date <='$end_new_date'";
                  
               }
               

        $query = $this->db->query("SELECT j.id,j.`order_id`,j.`date`,j.`price`,c.`full_name` FROM `job_master` j LEFT JOIN `customer_master` c ON c.`id`=j.`cust_fk` WHERE j.status !='0'  AND j.`portal` IN ('android','ios') $temp");
        return $query->num_rows();
    
    }
 function getwallet($reg_id=null,$ord_id=null,$customer_name=null,$start_date=null,$end_date=null,$one=null,$two=null) {
      $temp = "";
            
                if($reg_id != ''){
                     $temp .= " AND j.id ='$reg_id' ";
                   
                }
             if($ord_id != ''){
                  $temp .= " AND j.order_id ='$ord_id' ";
                    
                }
                 if($customer_name != ''){
                   $temp .= " AND c.full_name like '%$customer_name%'";
                    
                }
                if($start_date != ''){
					$old_date  = explode('/',$start_date);
               $o_date = $old_date[2].'-'.$old_date[1].'-'.$old_date[0];
               $new_date = $o_date ." 00:00:00";
                    $temp .= " AND j.date >='$new_date'";
                   
                }
                 if($end_date != ''){
					 $old_end_date  = explode('/',$end_date);
               $o_end_date = $old_end_date[2].'-'.$old_end_date[1].'-'.$old_end_date[0];
               $end_new_date = $o_end_date ." 23:59:59";
                     $temp .= " AND j.date <='$end_new_date'";
                  
               }
        /* $query = $this->db->query("SELECT j.id,j.`order_id`,j.`date`,j.`price`,c.`full_name`,w.`credit` FROM `job_master` j LEFT JOIN `customer_master` c ON c.`id`=j.`cust_fk` LEFT JOIN `wallet_master` AS w ON w.`job_fk`=j.`id` AND w.`status`='1'  WHERE j.status !='0'  AND j.`portal` IN ('android','ios')  GROUP BY j.id ORDER BY j.id DESC LIMIT $two,$one"); */
        $query = $this->db->query("SELECT j.id,j.`order_id`,j.`date`,j.`price`,c.`full_name` FROM `job_master` j LEFT JOIN `customer_master` c ON c.`id`=j.`cust_fk` WHERE j.status !='0'  AND j.`portal` IN ('android','ios') $temp GROUP BY j.id ORDER BY j.id DESC LIMIT $two,$one");
        return $query->result_array();
    }
    function getwallettotal($jobid=null) {
        
        $query = $this->db->query("SELECT SUM(w.`credit`) AS credit  FROM wallet_master w   WHERE w.`job_fk`='$jobid' AND STATUS='1'");
        return $query->row();
    
    }
}

?>
