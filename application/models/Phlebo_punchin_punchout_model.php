<?php

class Phlebo_punchin_punchout_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function num_row($fullname = null, $date = null, $end_date = null) {
        $qry = "SELECT phlabo_timer.*,`phlebo_master`.`name` FROM `phlabo_timer` INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` WHERE 1=1 ";
        if ($fullname != "") {
            $qry .= " AND name LIKE '%" . $fullname . "%' ";
        }
        if ($date != "") {
            $qry .= " AND start_date>='" . $date . "' ";
        }
        if ($end_date != "") {
            $qry .= " AND start_date<='" . $end_date . "' ";
        }
        $qry .= " AND `phlabo_timer`.status='1' ORDER BY `phlabo_timer`.id DESC";
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function insert($data) {

        $this->db->insert('package_category', $data);
        return true;
        return $this->db->insert_id();
    }

    public function delete_pc($pcid, $data) {
        $this->db->where(array('id' => $pcid));
        $this->db->update('package_category', $data);
        return true;
    }

    public function get_pc($pcid) {
        $query = $this->db->get_where('package_category', array('id' => $pcid));
        $data = $query->row();
        return $data;
    }

    public function update($pcid, $data) {
        $this->db->set($data);
        $this->db->where("id", $pcid);
        $this->db->update("package_category", $data);
        return true;
    }

    public function search($fullname, $date, $end_date, $one, $two) {

        /*$qry = "SELECT phlabo_timer.*,`phlebo_master`.`name`,
            MIN(phlabo_timer.start_time) As start_time_new,
            MAX(phlabo_timer.stop_time) As stop_time_new,
            SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(phlabo_timer.stop_time,phlabo_timer.start_time)))) As 
Total_working_hour
            FROM `phlabo_timer` 
            INNER JOIN `phlebo_master` 
			ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` 
            WHERE 1=1 ";*/
			
		$qry = "SELECT `phlabo_timer`.*, `phlebo_master`.`name`,
		`phlabo_timer`.start_time As start_time_new, `phlabo_timer`.stop_time As stop_time_new, 
		TIMEDIFF(`phlabo_timer`.stop_time,`phlabo_timer`.start_time) As Total_working_hour
		FROM `phlabo_timer`
		INNER JOIN `phlebo_master`
		ON `phlebo_master`.`id`=`phlabo_timer`.`user_fk`
		WHERE 1=1 ";
		
        if ($fullname != "") {
            $qry .= " AND `phlebo_master`.`name` LIKE '%" . $fullname . "%' ";
        }
        if ($date != "") {
            $qry .= " AND `phlabo_timer`.start_date>='" . $date . "' ";
        }
        if ($end_date != "") {
            $qry .= " AND `phlabo_timer`.start_date<='" . $end_date . "' ";
        }


        $qry .= " AND `phlabo_timer`.status='1' AND `phlabo_timer`.in_riding!=''  
				ORDER BY `phlabo_timer`.id DESC LIMIT $two,$one";
		//GROUP BY phlebo_master.id,phlabo_timer.start_date
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }


//    public function search($fullname, $date, $end_date, $one, $two) {
//
//        $qry = "SELECT phlabo_timer.*,`phlebo_master`.`name`, 
//            phlabo_timer.start_time As start_time_new, 
//            phlabo_timer.stop_time As stop_time_new, 
//            SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF(phlabo_timer.stop_time,phlabo_timer.start_time))) As 
//       Total_working_hour,doctor_master.full_name 
//            FROM `phlabo_timer` 
//            INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` 
//                LEFT JOIN `phlebo_assign_job` ON `phlebo_assign_job`.`phlebo_fk`=`phlebo_master`.`id` 
//            LEFT JOIN `job_master` ON `job_master`.`doctor`=`phlebo_assign_job`.`job_fk` 
//                LEFT JOIN `doctor_master` ON `doctor_master`.`id`=`job_master`.`doctor` 
//            WHERE 1=1 ";
//
//        if ($fullname != "") {
//            $qry .= " AND `phlebo_master`.`name` LIKE '%" . $fullname . "%' ";
//        }
//        if ($date != "") {
//            $qry .= " AND start_date>='" . $date . "' ";
//        }
//        if ($end_date != "") {
//            $qry .= " AND start_date<='" . $end_date . "' ";
//        }
//
//
////        $qry .= " AND `phlabo_timer`.status='1' AND in_riding!='' group by phlebo_master.id,phlabo_timer.start_date ORDER BY 
////`phlabo_timer`.id DESC LIMIT $two,$one";
//        $qry .= " AND `phlabo_timer`.status='1' AND in_riding!='' GROUP BY phlabo_timer.id  ORDER BY `phlabo_timer`.id  DESC LIMIT $two,$one";
//
//        $query = $this->db->query($qry);
//        $data['user'] = $query->result_array();
//        return $data['user'];
//    }

    public function get_details($id) {
        $qry = "SELECT phlabo_timer.*,`phlebo_master`.`name`,`phlebo_master`.`mobile` FROM `phlabo_timer` INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` WHERE 1=1 ";

        $qry .= " AND `phlabo_timer`.status='1' AND phlabo_timer.id='" . $id . "' ORDER BY `phlabo_timer`.id DESC";
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    public function csv_search($fullname = null, $date = null, $end_date = null) {

        /*$qry = "SELECT phlabo_timer.*,`phlebo_master`.`name`,
            MIN(phlabo_timer.start_time) As start_time_new,
            MAX(phlabo_timer.stop_time) As stop_time_new,
            SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(phlabo_timer.stop_time,phlabo_timer.start_time)))) As 
Total_working_hour
            FROM `phlabo_timer` 
            INNER JOIN `phlebo_master` ON `phlabo_timer`.`user_fk`=`phlebo_master`.`id` 
            WHERE 1=1 ";*/
		
		$qry = "SELECT `phlabo_timer`.*, `phlebo_master`.`name`,
		`phlabo_timer`.start_time As start_time_new, `phlabo_timer`.stop_time As stop_time_new, 
		TIMEDIFF(`phlabo_timer`.stop_time,`phlabo_timer`.start_time) As Total_working_hour
		FROM `phlabo_timer`
		INNER JOIN `phlebo_master`
		ON `phlebo_master`.`id`=`phlabo_timer`.`user_fk`
		WHERE 1=1 ";

        if ($fullname != "") {
            //echo $fullname ; exit;
            $qry .= " AND `phlebo_master`.`name` LIKE '%" . $fullname . "%' ";
        }
        if ($date != "") {
            //echo $date ; exit;
            $qry .= " AND `phlabo_timer`.start_date>='" . $date . "' ";
        }
        if ($end_date != "") {
            //echo $end_date ; exit;
            $qry .= " AND `phlabo_timer`.start_date<='" . $end_date . "' ";
        }


        $qry .= " AND `phlabo_timer`.status='1'  
		ORDER BY `phlabo_timer`.id DESC";
		
		//GROUP BY `phlebo_master`.id, `phlabo_timer`.start_date
        $query = $this->db->query($qry);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function create_unique_slug($string, $table, $field = 'slug', $key = NULL, $value = NULL) {
        $t = & get_instance();
        $slug = url_title($string);
        $slug = strtolower($slug);
        $i = 0;
        $params = array();
        $params[$field] = $slug;
        if ($key)
            $params["$key !="] = $value;
        while ($t->db->where($params)->get($table)->num_rows()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug))
                $slug .= '-' . ++$i;
            else
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);
            $params [$field] = $slug;
        } return $slug;
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);
        $data['user'] = $query->result_array();
        return $data['user'];
    }

}

?>