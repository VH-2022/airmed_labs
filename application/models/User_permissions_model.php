<?php
class User_permissions_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function insert_master($table_name, $data)
    {
        
         $this->db->insert($table_name, $data);
        return $this->db->insert_id();
    }

    public function master_fun_update($tablename, $cid, $data)
    {
        
         $this->db->where($cid[0], $cid[1]);
         $this->db->update($tablename, $data);
        return 1;
    }

    public function userPermission($userID, $permissionId)
    {
        
        $query =  $this->db->query("SELECT * FROM `user_permissions` where `user_id`=" . $userID . " and `permission_id`=" . $permissionId . " and deleted_at IS NULL LIMIT 1");
        return $query->result_array();
    }

    public function userPermissionIds($userID)
    {
        
        $query =  $this->db->query("SELECT * FROM `user_permissions` where `user_id`=" . $userID . "  and deleted_at IS NULL ");

        return $query->result_array();
    }
}
