<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_model extends CI_Model {


    function insert_staff($data)
    {
        $this->db->insert("staff_tbl",$data);
        return $this->db->insert_id();
    }

    function select_staff()
    {
        $this->db->order_by('staff_tbl.id', 'DESC');
        $this->db->select("staff_tbl.*, department_tbl.department_name, COALESCE(rank_tbl.rank_name, 'No Rank') AS rank_name");
        $this->db->from("staff_tbl");
        $this->db->join("department_tbl", 'department_tbl.id = staff_tbl.department_id', 'left');
        $this->db->join("rank_tbl", 'rank_tbl.id = staff_tbl.rank_id', 'left');  // Use LEFT JOIN for rank_tbl
        $qry = $this->db->get();
        if ($qry->num_rows() > 0)
        {
            $result = $qry->result_array();
            return $result;
        }
    }

    function select_staff_byID($id)
    {
        $this->db->where('staff_tbl.id',$id);
        $this->db->select("staff_tbl.*,department_tbl.department_name");
        $this->db->from("staff_tbl");
        $this->db->join("department_tbl",'department_tbl.id=staff_tbl.department_id');
        $qry=$this->db->get();
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function select_staff_byEmail($email)
    {

        $this->db->where('email',$email);
        $qry=$this->db->get('staff_tbl');
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function select_staff_byDept($dpt)
    {
        $this->db->select("staff_tbl.*, department_tbl.department_name, salary_tbl.allowance");
        $this->db->from("staff_tbl");
        $this->db->join("department_tbl", 'department_tbl.id = staff_tbl.department_id');
        $this->db->join("salary_tbl", 'salary_tbl.staff_id = staff_tbl.id', 'left'); // Left join to include all staff
        $this->db->where('staff_tbl.department_id', $dpt); // Ensure the department filter is applied correctly
        
        $qry = $this->db->get();
            
        if ($qry->num_rows() > 0) {
            $result = $qry->result_array();
            return $result;
        } else {
            return []; // Return an empty array if no results
        }
    }
    


    function delete_staff($id)
    {
        $this->db->where('id', $id);
        $this->db->delete("staff_tbl");
        $this->db->affected_rows();
    }

    
    function update_staff($data,$id)
    {
        $this->db->where('id', $id);
        $this->db->update('staff_tbl',$data);
        $this->db->affected_rows();
    }

    

    
    




}
