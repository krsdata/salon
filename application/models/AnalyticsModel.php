<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnalyticsModel extends CI_Model {

	//Generic function which will give all details by primary key of table
    public function DetailsById($id,$table_name,$where)
    {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where,$id);
        $this->db->limit(1);
        
        //execute the query
        $query = $this->db->get();
        
        if ($query->num_rows() == 1){
            return $query->row_array();
        } 
        else{
            return false;
        }
    }

    //Generic function
    public function MultiWhereSelect($table_name,$where_array){
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where_array);
        
        //execute the query
        $query = $this->db->get();
        
        return $query->result_array();
    }

    //Generic function
    public function FullTable($table_name){
        $query = $this->db->get($table_name);
        return $query->result_array(); 
    }

    //Generic function
    public function Update($data,$table_name,$where){
        $this->db->where($where, $data[$where]);
        $res = $this->db->update($table_name, $data);
        return $res;
    }

    //Generic function
    public function Insert($data,$table_name){
        $res = $this->db->insert($table_name,$data);
        return $res;    
    }
}