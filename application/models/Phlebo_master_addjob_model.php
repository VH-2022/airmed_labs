<?php

Class Phlebo_master_addjob_model extends CI_Model {

    public function csv_report($start_date = null, $end_date = null, $phlebo = null, $city = null, $sm_coll = null) {

        $old_date = explode("/", $start_date);
        $new_date = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];

        $second_old_date = explode("/", $end_date);

        $second_new_date = $second_old_date[2] . '-' . $second_old_date[1] . '-' . $second_old_date[0];

        $qry = "SELECT paj.id AS sid,paj.*,   
      ph.name AS PhleboName,ph.test_city,   
      job.id,job.cust_fk,job.order_id,job.price,job.`payable_amount`,  
      cu.full_name,     
      sub.id,sub.start_time ,sub.end_time ,
      sf.name as SampleName,
      do.full_name as DoctorName
      FROM phlebo_assign_job AS paj       
      LEFT JOIN phlebo_master AS ph ON ph.id = paj.phlebo_fk      
      LEFT JOIN job_master AS job ON job.id = paj.job_fk      
      LEFT JOIN customer_master AS cu ON cu.id = job.cust_fk 
      LEFT JOIN phlebo_time_slot AS sub ON sub.id = paj.time_fk
      LEFT JOIN doctor_master AS do ON do.id = job.doctor
      LEFT JOIN sample_from AS sf ON sf.id = job.sample_from
      WHERE paj.status = 1 and job.status!='0'";

        if ($start_date != '' && $end_date != '') {
            $qry .= " AND paj.date >= '" . $new_date . "' AND paj.date <= '" . $second_new_date . "'";
        }
        if ($phlebo != '') {
            $qry .= " AND paj.phlebo_fk = '$phlebo'";
        }
        if ($city != '') {
            $qry .= " AND ph.test_city = '$city'";
        }
        if ($sm_coll != '') {
            $qry .= " AND job.sample_from = '$sm_coll'";
        }
        $qry .= " order by paj.job_fk ASC";

        $query = $this->db->query($qry);
        //print_r($query);die;
        $query1 = $query->result_array();
        return $query1;
    }

    function get_val($query1 = null) {
        $query = $this->db->query($query1);

        $data['user'] = $query->result_array();
        return $data['user'];
    }

    function report($start_date = null, $end_date = null, $city = null, $phlebo_name = null, $sample = null) {

        $date1 = explode("/", $start_date);
        $new_date = $date1[2] . '-' . $date1[1] . '-' . $date1[0];

        $date2 = explode("/", $end_date);
        $second_new_date = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
        //print_r($date2);die;

        $qry = "select j.id,j.order_id, DATE_FORMAT(j.date, '%d %b %Y %h:%i %p') AS date, 
            j.status,c.full_name,pm.name AS PhleboName, dm.full_name AS DoctorName, 
            GROUP_CONCAT(distinct t.test_name SEPARATOR  ',') testname, 
            GROUP_CONCAT(distinct p.title SEPARATOR  ',') packagename, 
            GROUP_CONCAT(distinct p.id SEPARATOR  ',') packagid,j.note,j.price,sf.name as SampleName 
            from job_master j 
            LEFT JOIN sample_from AS sf ON sf.id = j.sample_from 
            LEFT JOIN job_test_list_master jtl ON jtl.job_fk=j.`id` 
            LEFT JOIN customer_master c ON c.id=j.`cust_fk` 
            LEFT JOIN doctor_master dm ON dm.id=j.`doctor` AND dm.status='1' 
            LEFT JOIN phlebo_master pm ON pm.id=j.`phlebo_added` AND pm.status='1' 
            LEFT JOIN test_master t ON t.id=jtl.test_fk 
            LEFT JOIN book_package_master pb ON pb.job_fk = j.id 
            LEFT JOIN package_master p  ON p.id = pb.package_fk 
            LEFT JOIN phlebo_assign_job paj ON paj.job_fk = j.id  
            where 1=1 AND j.status!='0' AND j.phlebo_added='$phlebo_name'";

        if ($start_date != "" || $end_date != "") {
            $qry .= " AND j.date >= '" . $new_date . "' AND j.date <= '" . $second_new_date . "' ";
        }

        if ($city != "") {
            //$qry .= " AND ph.test_city = '$city'";
        }
        if ($phlebo_name != "") {
            //$qry .= " AND job.phlebo_added = '$phlebo_name'";
        }

        if ($sample != "") {
            $qry .= " AND sf.id = '$sample'";
        }

        $qry .= " GROUP BY j.id  ORDER BY j.id DESC";
        $query = $this->db->query($qry);

        $query1 = $query->result_array();
        return $query1;
    }

    function job_report($job_start_date = null, $job_end_date = null, $phlebo_name = null, $sample = null) {
        $old_date = explode("/", $job_start_date);
        $date1 = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];
        $new_date = $date1 . " 00:00:00";

        $sub_old_date = explode("/", $job_end_date);
        $date2 = $sub_old_date[2] . '-' . $sub_old_date[1] . '-' . $sub_old_date[0];
        $sub_new_date = $date2 . " 23:59:59";

        $qry = "SELECT jmra.*,
                  jmra.id AS jid,
                  phl.name AS PhleboName,
                  
                  par.address,
                  job.order_id,
                  job.id as jid,
                  job.cust_fk,
                  cu.full_name,
                  sf.name as SampleName,
                  do.full_name as DoctorName
                    FROM job_master_receiv_amount AS jmra 
                  LEFT JOIN phlebo_master AS phl ON jmra.phlebo_fk = phl.id 
                  LEFT JOIN job_master AS job ON job.id = jmra.job_fk
                  LEFT JOIN customer_master AS cu ON cu.id = job.cust_fk 
                  LEFT JOIN phlebo_assign_job AS par ON par.job_fk = job.id
                  LEFT JOIN sample_from AS sf ON sf.id = job.sample_from
                  LEFT JOIN doctor_master AS do ON do.id = job.doctor
                  where jmra.status = 1 AND jmra.phlebo_fk  IS NOT NULL";

        if ($job_start_date != "") {
            $qry .= " AND jmra.createddate >='" . $new_date . "'";
        }
        if ($job_end_date != "") {
            $qry .= " AND jmra.createddate <='" . $sub_new_date . "'";
        }
        if ($phlebo_name != "") {
            $qry .= " AND jmra.phlebo_fk ='$phlebo_name'";
        }

        if ($sample != "") {
            $qry .= " AND job.sample_from ='$sample'";
        }
        $qry .= " order by jmra.id ASC";

        $query = $this->db->query($qry);

        $query1 = $query->result_array();
        return $query1;
    }

    function sub_job_csv($job_start_date = null, $job_end_date = null, $phlebo = null, $sm_coll = null) {
        $old_date = explode("/", $job_start_date);
        $date1 = $old_date[2] . '-' . $old_date[1] . '-' . $old_date[0];
        $new_date = $date1 . " 00:00:00";

        $sub_old_date = explode("/", $job_end_date);
        $date2 = $sub_old_date[2] . '-' . $sub_old_date[1] . '-' . $sub_old_date[0];
        $sub_new_date = $date2 . " 23:59:59";

        $qry = "SELECT jmra.*,
                  jmra.id AS jid,
                  phl.name AS PhleboName,
                  par.address,
                  job.order_id,
                  job.id as jid,
                  job.cust_fk,
                  cu.full_name,
                  sf.name as SampleName,
                  do.full_name as DoctorName
                    FROM job_master_receiv_amount AS jmra 
                  LEFT JOIN phlebo_master AS phl ON jmra.phlebo_fk = phl.id 
                  LEFT JOIN job_master AS job ON job.id = jmra.job_fk
                  LEFT JOIN customer_master AS cu ON cu.id = job.cust_fk 
                  LEFT JOIN phlebo_assign_job AS par ON par.job_fk = job.id
                  LEFT JOIN sample_from AS sf ON sf.id = job.sample_from
                  LEFT JOIN doctor_master AS do ON do.id = job.doctor
                  where jmra.status = 1 AND jmra.phlebo_fk  IS NOT NULL";

        if ($job_start_date != "") {
            $qry .= " AND jmra.createddate >='" . $new_date . "'";
        }
        if ($job_end_date != "") {
            $qry .= " AND jmra.createddate <='" . $sub_new_date . "'";
        }
        if ($phlebo != "") {
            $qry .= " AND jmra.phlebo_fk ='$phlebo'";
        }

        if ($sm_coll != "") {
            $qry .= " AND job.sample_from ='$sample'";
        }
        $qry .= " order by jmra.id ASC";

        $query = $this->db->query($qry);

        $query1 = $query->result_array();
        return $query1;
    }

}

?>
