<?php

Class Business_model extends CI_Model {

	function get_val($query1){
		$query = $this->db->query($query1);
		$data['user']  = $query->result_array();
		return $data['user'];
	}
        
        
       
        function master_get_search($start_date=null,$end_date=null,$city=null){
             $temp ='';
            if($start_date !=''){
               $odate = explode('/',$start_date);
               $new_date = $odate[2].'-'.$odate[1].'-'.$odate[0]. ' 00:00:00';
               $temp .=' AND lg.scan_date >="'.$new_date.'"';
            }
            if($end_date !=''){
                  $odate = explode('/',$end_date);
               $snew_date = $odate[2].'-'.$odate[1].'-'.$odate[0]. ' 23:59:59';
               $temp .=' AND lg.scan_date <="'.$snew_date.'"';
            }
            if($city !=''){
                $temp .=' AND cf.city ="'.$city.'"';
            }
            
            $query = $this->db->query("SELECT 
  cf.name AS ClientName,
  sj.`date`,
  sl.first_name,
  sl.last_name,
  tc.name AS City,
sum(sj.price) AS Revenue,
 COUNT(sj.id) as Booking
FROM
  collect_from AS cf 
  LEFT JOIN sales_user_master AS sl 
    ON sl.id = cf.sales_fk 
    AND sl.status = '1' 
    LEFT JOIN test_cities AS tc 
    ON tc.id = cf.city
    AND tc.status = '1'
    LEFT JOIN  logistic_log AS lg ON lg.`collect_from` = cf.id AND lg.`status`='1'
    LEFT JOIN  sample_job_master AS sj ON sj.`barcode_fk` = lg.id AND sj.`status`='1'
 
WHERE cf.status = '1' 
 $temp GROUP BY lg.`collect_from`");
            $data['user'] = $query->result_array();
		return $data['user'];
        }
        
        function master_get_b2c($start_date=null,$end_date=null,$city=null){
            
               $temp ='';
            if($start_date !=''){
               $odate = explode('/',$start_date);
               $new_date = $odate[2].'-'.$odate[1].'-'.$odate[0]. ' 00:00:00';
               $temp .=' AND sj.date >="'.$new_date.'"';
            }
            if($end_date !=''){
                  $odate = explode('/',$end_date);
               $snew_date = $odate[2].'-'.$odate[1].'-'.$odate[0]. ' 23:59:59';
               $temp .=' AND sj.date <="'.$snew_date.'"';
            }
            if($city !=''){
                $temp .=' AND br.city ="'.$city.'"';
            }
            
            $query = $this->db->query("SELECT 
  br.branch_name AS BranchName,
  bt.`name` AS BranchType,
  tc.name AS City,
  sum(sj.price) as Revenue,
  COUNT(sj.id) AS Booking 
FROM
  branch_master AS br 
  LEFT JOIN branch_type AS bt 
    ON br.branch_type_fk = bt.id 
    AND bt.status = '1' 
  LEFT JOIN test_cities AS tc 
    ON tc.id = br.city 
    AND tc.status = '1' 
  LEFT JOIN job_master AS sj 
    ON sj.branch_fk = br.id 
    AND sj.status != 0 and sj.model_type='1'
WHERE br.status = '1' 
 $temp 
GROUP BY sj.`branch_fk` ");
            $data['user'] = $query->result_array();
		return $data['user'];
        }
      
}