<?php

class M_kontak extends CI_Model {

    //get all
    public function get_all()
    {
        $this->db->select('*');
        $this->db->from('kontak');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
          $result = $query->row_array();
          $query->free_result();
          return $result;
        }
        return array();
    }

    //all role
    public function get_all_role()
    {
        $this->db->select('*');
        $this->db->from('com_role');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
          $result = $query->result_array();
          $query->free_result();
          return $result;
        }
        return array();
    }

    //get by username
    public function get_by_username($username)
    {
        $this->db->select('*');
        $this->db->from('com_user');
        $this->db->join('user', 'user.user_id = com_user.user_id', 'left');
        $this->db->join('com_role_user', 'com_role_user.user_id = user.user_id', 'left');
        $this->db->join('com_role', 'com_role_user.role_id = com_role.role_id', 'left');
        $this->db->where('com_user.user_name', $username);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
          $result = $query->row_array();
          $query->free_result();
          return $result;
        }
        return array();
    }

    //get by id
    public function get_by_id($user_id)
    {
        $this->db->select('com_user.user_id, com_user.mdb_name, com_user.user_name, com_user.user_mail, com_user.user_st, com_user.mdb, com_role.role_id ,com_user.mdd, com_role.role_nm, user.nama, user.alamat, user.jns_kelamin');
        $this->db->from('com_user');
        $this->db->join('user', 'user.user_id = com_user.user_id', 'left');
        $this->db->join('com_role_user', 'com_role_user.user_id = user.user_id', 'left');
        $this->db->join('com_role', 'com_role_user.role_id = com_role.role_id', 'left');
        $this->db->where('com_user.user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
          $result = $query->row_array();
          $query->free_result();
          return $result;
        }
        return array();
    }

    //count all
    public function count_all()
    {
        $this->db->select('*');
        $this->db->from('com_user');
        $this->db->join('user', 'user.user_id = com_user.user_id', 'left');
        $this->db->join('com_role_user', 'com_role_user.user_id = user.user_id', 'left');
        $this->db->join('com_role', 'com_role_user.role_id = com_role.role_id', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
          $result = $query->num_rows();
          return $result;
        }
        return 0;
    }

    //cek email
    public function is_exist_email($params)
    {
        $query = $this->db->get_where('com_user', array('user_mail' => $params));
        if ($query->num_rows() > 0) {
          $result = $query->row_array();
          $query->free_result();
          return $result;
        }
        return 0;
    }

    //cek username
    public function is_exist_username($params)
    {
        $query = $this->db->get_where('com_user', array('user_name' => $params));
        if ($query->num_rows() > 0) {
          $result = $query->row_array();
          $query->free_result();
          return $result;
        }
        return 0;
    }

    //insert
    public function insert($table ,$params)
    {
      return $this->db->insert($table, $params);
    }

    //delete
    public function delete($table ,$where)
    {
      $this->db->where($where);
      return $this->db->delete($table);
    }

    //update
    public function update($table, $params)
    {
      $this->db->set($params);
      return $this->db->update($table);
    }
  
    
}