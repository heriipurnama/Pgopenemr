<?php 

class M_upload extends CI_Model
{

    function get_insert($data){
       $this->db->insert('upload', $data);
       return TRUE;
    }

}