<?php

class Logistic_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    function getUser($user_id) {
        $query = $this->db->get_where("admin_master", array("id" => $user_id, "status" => "1"));
        return $query->row();
    }

    public function master_fun_get_tbl_val($dtatabase, $condition, $order, $limit = null) {
        $this->db->order_by($order[0], $order[1]);
        if ($limit != null) {
            $this->db->limit($limit[0], $limit[1]);
        }
        $query = $this->db->get_where($dtatabase, $condition);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function master_fun_insert($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $condition, $data) {
        $this->db->where($condition);
        $this->db->update($tablename, $data);
        return 1;
    }

    public function master_num_rows($table, $condition) {
        $query1 = $this->db->get_where($table, $condition);
        return $query1->num_rows();
    }

    public function get_server_time() {
        $query = $this->db->query("SELECT UTC_TIMESTAMP()");
        $data['user'] = $query->result_array();
        return $data['user'][0];
    }

    public function get_val($qry) {
        $query1 = $this->db->query($qry);
        $data['user'] = $query1->result_array();
        return $data['user'];
    }

    function sample_list($one = null, $two = null) {
        $limit = "";
        if ($one != null && $two != null) {
            $limit = " LIMIT $two,$one";
        }
        $qry = "SELECT `logistic_log`.*,`phlebo_master`.`name`,`phlebo_master`.`mobile`,`collect_from`.`name` AS `c_name` FROM `logistic_log` 
INNER JOIN `phlebo_master` ON `logistic_log`.`phlebo_fk`=`phlebo_master`.`id` 
INNER JOIN `collect_from` ON `logistic_log`.`collect_from`=`collect_from`.`id`
WHERE `phlebo_master`.`status`='1' AND `logistic_log`.`status`='1' ORDER BY logistic_log.id DESC" . $limit;
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    

    public function lab_num_rows($state_serch = null,$email=null) {

        $query = "SELECT * FROM collect_from WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '%$email%'";
        }
        $query .= " ORDER BY name asc";

        $result = $this->db->query($query);
        return $result->num_rows();
    }

    public function lab_data($state_serch = null,$email = null, $limit, $start) {

        $query = "SELECT * FROM collect_from WHERE status='1' ";

        if ($state_serch != "") {

            $query .= " AND name like '%$state_serch%'";
        }
        if ($email != "") {

            $query .= " AND email like '%$email%'";
        }
        $query .= " ORDER BY name asc";

        $result = $this->db->query($query);
        return $result->result_array();
    }

    public function srch_lab_list($limit, $start) {

        $query = $this->db->query("SELECT * FROM collect_from WHERE status='1' ORDER BY name ASC LIMIT $start , $limit ");
        return $query->result_array();
    }
function samplelist_numrow($type = null, $name = null, $barcode = null, $date = null,$todate=null, $from = null,$patientsname = null,$salesperson=null,$sendto=null,$city=null,$status=null) {
		
		echo "ok";
		die();
$this->db->select("l.id,p.`name`,p.`mobile`,c.`name` AS `c_name`,tc.name as tetst_city_name,d.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=l.id and status='1' ) as treport,(SELECT name FROM `admin_master` a where a.status='1' and a.id=d.lab_fk) as desti_lab1,j.customer_name,j.payable_amount,CONCAT(s.first_name, ' ',s.last_name) As salesname");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('sales_user_master s','s.id=c.sales_fk','left');
$this->db->join('b2b_test_cities tc','c.city=tc.id','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');
if ($type["type"] == 4) {
$this->db->join('sample_destination_lab d','l.id=d.job_fk and d.lab_fk='.$type["id"].'','INNER');	
}

if ($type["type"] == 3) { if (!empty($sendto)){ $this->db->join('sample_destination_lab d',"l.id=d.job_fk and d.lab_fk='$sendto'",'INNER'); }else{ $this->db->join('sample_destination_lab d','l.id=d.job_fk','left'); } }
$this->db->where('l.status','1');

if (!empty($name)) { $this->db->like('p.name',$name);    }
if (!empty($barcode)) {	 $this->db->like('l.barcode',$barcode);	}
if (!empty($patientsname)){$this->db->like('j.customer_name',$patientsname);}

if (!empty($date)) {
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));	
        }
if (!empty($todate)) {
	
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($todate)));	

}
/* if(!empty($from)){ $this->db->like('c.name',$from); } */
if(!empty($from)){ $this->db->where('l.collect_from',$from); }
if(!empty($salesperson)){ $this->db->where('c.sales_fk',$salesperson);}
		
$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$query = $this->db->get();
 return $query->num_rows();
     
	 }
function sample_list_num($type = null, $name = null, $barcode = null, $date = null,$todate=null, $from = null,$patientsname = null,$salesperson=null,$sendto=null,$city=null,$status=null,$one = null, $two = null) {
	$this->db->select("l.*,p.`name`,p.`mobile`,c.`name` AS `c_name`,tc.name as tetst_city_name,d.lab_fk as desti_lab,(select count(b2b_jobspdf.id) from b2b_jobspdf where b2b_jobspdf.job_fk=l.id and status='1' ) as treport,(SELECT name FROM `admin_master` a where a.status='1' and a.id=d.lab_fk) as desti_lab1,j.customer_name,j.payable_amount,CONCAT(s.first_name, ' ',s.last_name) As salesname");
$this->db->from('logistic_log AS l');
$this->db->join('phlebo_master p','p.id=l.phlebo_fk and p.status="1"','left');
$this->db->join('collect_from c','c.id=l.collect_from','left');
$this->db->join('sales_user_master s','s.id=c.sales_fk','left');
$this->db->join('b2b_test_cities tc','c.city=tc.id','left');
$this->db->join('sample_job_master j','j.barcode_fk=l.id','left');
if ($type["type"] == 4) {
$this->db->join('sample_destination_lab d','l.id=d.job_fk and d.lab_fk='.$type["id"].'','INNER');	
}

if ($type["type"] == 3) { if (!empty($sendto)){ $this->db->join('sample_destination_lab d',"l.id=d.job_fk and d.lab_fk='$sendto'",'INNER'); }else{ $this->db->join('sample_destination_lab d','l.id=d.job_fk','left'); } }
$this->db->where('l.status','1');

if (!empty($name)) { $this->db->like('p.name',$name);    }
if (!empty($barcode)) {	 $this->db->like('l.barcode',$barcode);	}
if (!empty($patientsname)){$this->db->like('j.customer_name',$patientsname);}

if (!empty($date)) {
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') >=", date("Y-m-d", strtotime($date)));	
        }
if (!empty($todate)) {
	
	/* $this->db->where('DATE_FORMAT(l.scan_date,"%Y-%m-%d")',date("Y-m-d",strtotime($date))); */
$this->db->where("DATE_FORMAT(l.scan_date,'%Y-%m-%d') <=",date("Y-m-d", strtotime($todate)));	

}
/* if(!empty($from)){ $this->db->like('c.name',$from); } */
if(!empty($from)){ $this->db->where('l.collect_from',$from); }
if(!empty($salesperson)){ $this->db->where('c.sales_fk',$salesperson);}
		
$this->db->GROUP_BY('l.id');
$this->db->order_by('l.id','desc');
$this->db->limit($one,$two);
$query = $this->db->get();
 return $query->result_array();
     } 
}

?>
