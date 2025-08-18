<?php

Class Feedback_model extends CI_Model {

    public function master_get_search($name=null, $email=null, $subject=null,$mobile=NULL,$one,$two) {

        $query = "SELECT * from feedback where status='1'";
        if ($name != "") {
            $query .= " AND name LIKE '%$name%'";
        }
        if ($email != NULL) {
            $query .= " AND email ='$email'";
        }
          if($subject !=''){
          $query .= " AND subject LIKE '%".$subject."%'";
        }
        if($mobile !=''){
$query .= " AND phone = '".$mobile."'";
        }

        $query .= " ORDER BY id DESC LIMIT $two,$one";

        $query = $this->db->query($query);
        return $query->result_array();
    }


    public function num_row($name=null, $email=null,$subject=null, $mobile=null) {
        $query = "select * from feedback where status='1'";
        if ($name != "") {
            $query .= " AND name LIKE '%$name%'";
        }

        if ($email != NULL) {
            $query .= " AND email = '$name'";
        }
        
       
          if($subject !=''){
          $query .= " AND subject LIKE '%".$subject."%'";
        }
        if($mobile !=''){
$query .= " AND phone = '".$mobile."'";
        }
        $query .= "  ORDER BY id DESC ";
        $query = $this->db->query($query);
        return $query->num_rows();
    }
public function master_tbl_update($tablename, $cid, $data) {
        $this->db->where(array('id' => $cid));
        $this->db->update($tablename, $data);
        return 1;
    }
  
}

?>
