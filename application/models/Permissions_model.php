<?php
class Permissions_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function permissions()
    {
        
        $query = $this->db->query("SELECT * FROM `permissions`");
        return $query->result_array();
    }

    public function permissionsByIds($ids)
    {
        
        $query = $this->db->query("SELECT * FROM `permissions` where id In (" . $ids . ")");
        return $query->result_array();
    }
    public function permissionsByNames($names)
    {
        
        $query = $this->db->query("SELECT * FROM `permissions` where permission In (" . $names . ")");
        return $query->result_array();
    }
}
