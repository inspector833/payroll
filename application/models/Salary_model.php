<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary_model extends CI_Model {


    function insert_salary($data)
    {
        $this->db->insert("salary_tbl",$data);
        $this->db->affected_rows();
    }

    function select_salary()
    {
        $this->db->order_by('staff_tbl.id','DESC');
        $this->db->select("salary_tbl.*,staff_tbl.staff_name,staff_tbl.pic,department_tbl.department_name,
        (department_tbl.salary) AS department_salary, 3 AS tax,
        (department_tbl.salary + salary_tbl.basic_salary + salary_tbl.allowance) AS sub_total,
        ((3/100)*(department_tbl.salary + salary_tbl.basic_salary + salary_tbl.allowance)) AS tax_amount


        ");
        $this->db->from("salary_tbl");
        $this->db->join("staff_tbl",'staff_tbl.id=salary_tbl.staff_id');
        $this->db->join("department_tbl",'department_tbl.id=staff_tbl.department_id');
        $qry=$this->db->get();
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function select_salary_byID($id)
{
    $this->db->where('salary_tbl.id', $id);
    $this->db->select("
        salary_tbl.*, 
        staff_tbl.staff_name, 
        staff_tbl.city, 
        staff_tbl.state, 
        staff_tbl.country, 
        staff_tbl.mobile, 
        staff_tbl.email, 
        department_tbl.department_name,
        (department_tbl.salary) AS calculated_salary,
    3 as tax,
    ((3/100)*(department_tbl.salary + salary_tbl.basic_salary + salary_tbl.allowance)) AS tax_amount,
    (department_tbl.salary + salary_tbl.basic_salary + salary_tbl.allowance) AS sub_total,

    ");
    $this->db->from("salary_tbl");
    $this->db->join("staff_tbl", 'staff_tbl.id = salary_tbl.staff_id');
    $this->db->join("department_tbl", 'department_tbl.id = staff_tbl.department_id');
    $qry = $this->db->get();

    if ($qry->num_rows() > 0) {
        return $qry->result_array();
    } else {
        return [];
    }
}


    function select_salary_byStaffID($staffid)
{
    $this->db->where('salary_tbl.staff_id', $staffid);
    $this->db->select("
        salary_tbl.*,
        staff_tbl.staff_name,
        staff_tbl.city,
        staff_tbl.state,
        staff_tbl.country,
        staff_tbl.mobile,
        staff_tbl.email,
        department_tbl.department_name,
        department_tbl.salary AS department_salary
    ");
    $this->db->from("salary_tbl");
    $this->db->join("staff_tbl", 'staff_tbl.id = salary_tbl.staff_id');
    $this->db->join("department_tbl", 'department_tbl.id = staff_tbl.department_id');
    
    $qry = $this->db->get();

    if ($qry->num_rows() > 0) {
        $result = $qry->result_array();

        // Multiply the department salary by the basic salary for each result
        foreach ($result as &$row) {
            $row['calculated_salary'] = $row['department_salary'];
            $row['tax'] = 3;

        }

        return $result;
    } else {
        return [];  // Return an empty array if no results are found
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

    function sum_salary()
    {
        $this->db->select_sum('total');
        $qry = $this->db->get('salary_tbl');
        if($qry->num_rows()>0)
        {
            $result=$qry->result_array();
            return $result;
        }
    }

    function delete_salary($id)
    {
        $this->db->where('id', $id);
        $this->db->delete("salary_tbl");
        $this->db->affected_rows();
    }

    
    function update_staff($data,$id)
    {
        $this->db->where('id', $id);
        $this->db->update('staff_tbl',$data);
        $this->db->affected_rows();
    }

    

    
    




}
