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
    $this->db->order_by('staff_tbl.id', 'DESC');
    
    // Join with rank_tbl using LEFT JOIN and fetch basic_salary_tbl salary separately
    $this->db->select("salary_tbl.*, 
                        staff_tbl.staff_name, 
                        staff_tbl.first_name, 
                        staff_tbl.middle_name, 
                        staff_tbl.last_name, 
                        staff_tbl.pic, 
                        department_tbl.department_name, 
                        department_tbl.salary AS department_salary, 
                        (SELECT salary FROM basic_salary_tbl LIMIT 1) AS basic_salary, 
                        COALESCE(rank_tbl.percentage, 0) AS rank_percentage, 
                        3 AS tax,
                        (department_tbl.salary + salary_tbl.basic_salary + (SELECT salary FROM basic_salary_tbl LIMIT 1) + salary_tbl.allowance) AS sub_total,
                        ((3 / 100) * (department_tbl.salary + salary_tbl.basic_salary + (SELECT salary FROM basic_salary_tbl LIMIT 1) + salary_tbl.allowance)) AS tax_amount,
                        ((SELECT salary FROM basic_salary_tbl LIMIT 1) + ( COALESCE(rank_tbl.percentage, 0)/100)*(SELECT salary FROM basic_salary_tbl LIMIT 1)) AS single_spine,
                        ((15/100)* ((SELECT salary FROM basic_salary_tbl LIMIT 1) + ( COALESCE(rank_tbl.percentage, 0)/100)*(SELECT salary FROM basic_salary_tbl LIMIT 1))) AS retention
                        ");

    $this->db->from("salary_tbl");
    $this->db->join("staff_tbl", 'staff_tbl.id = salary_tbl.staff_id');
    $this->db->join("department_tbl", 'department_tbl.id = staff_tbl.department_id');
    $this->db->join("rank_tbl", 'rank_tbl.id = staff_tbl.rank_id', 'LEFT'); // Use LEFT JOIN to include all staff

    $qry = $this->db->get();
    
    if ($qry->num_rows() > 0)
    {
        $result = $qry->result_array();
        return $result;
    }
}


    function select_salary_byID($id)
{
    $this->db->where('salary_tbl.id', $id);
    $this->db->select("
        salary_tbl.*, 
        staff_tbl.staff_name, 
        staff_tbl.first_name, 
        staff_tbl.last_name, 
        staff_tbl.middle_name, 
        staff_tbl.city, 
        staff_tbl.state, 
        staff_tbl.country, 
        staff_tbl.mobile, 
        staff_tbl.email, 
        department_tbl.department_name,
        (department_tbl.salary) AS calculated_salary,
        (SELECT salary FROM basic_salary_tbl LIMIT 1) AS basic_salary, 
        COALESCE(rank_tbl.percentage, 0) AS rank_percentage,        
    3 as tax,
    ((3/100)*(department_tbl.salary + salary_tbl.basic_salary + salary_tbl.allowance)) AS tax_amount,
    (department_tbl.salary + salary_tbl.basic_salary + salary_tbl.allowance) AS sub_total,
    ((15/100)* ((SELECT salary FROM basic_salary_tbl LIMIT 1) + ( COALESCE(rank_tbl.percentage, 0)/100)*(SELECT salary FROM basic_salary_tbl LIMIT 1))) AS retention,
    ((SELECT salary FROM basic_salary_tbl LIMIT 1) + ( COALESCE(rank_tbl.percentage, 0)/100)*(SELECT salary FROM basic_salary_tbl LIMIT 1)) AS single_spine,

    ");
    $this->db->from("salary_tbl");
    $this->db->join("staff_tbl", 'staff_tbl.id = salary_tbl.staff_id');
    $this->db->join("department_tbl", 'department_tbl.id = staff_tbl.department_id');
    $this->db->join("rank_tbl", 'rank_tbl.id = staff_tbl.rank_id', 'LEFT'); // Use LEFT JOIN to include all staff

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
        department_tbl.salary AS department_salary,
        (SELECT salary FROM basic_salary_tbl LIMIT 1) AS basic_salary, 
        ((15/100)* ((SELECT salary FROM basic_salary_tbl LIMIT 1) + ( COALESCE(rank_tbl.percentage, 0)/100)*(SELECT salary FROM basic_salary_tbl LIMIT 1))) AS retention,
        ((SELECT salary FROM basic_salary_tbl LIMIT 1) + ( COALESCE(rank_tbl.percentage, 0)/100)*(SELECT salary FROM basic_salary_tbl LIMIT 1)) AS single_spine,
    
    ");
    $this->db->from("salary_tbl");
    $this->db->join("staff_tbl", 'staff_tbl.id = salary_tbl.staff_id');
    $this->db->join("department_tbl", 'department_tbl.id = staff_tbl.department_id');
    $this->db->join("rank_tbl", 'rank_tbl.id = staff_tbl.rank_id', 'LEFT'); // Use LEFT JOIN to include all staff

    
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

    
    public function get_salary_by_staff_id($staff_id)
    {
        $query = $this->db->get_where('salary_tbl', array('staff_id' => $staff_id));
        return $query->row(); // Returns a single result or null
    }

    public function update_salary($staff_id, $data)
    {
        $this->db->where('staff_id', $staff_id);
        return $this->db->update('salary_tbl', $data);
    }

    // public function insert_salary($data)
    // {
    //     return $this->db->insert('salaries', $data);
    // }


    public function get_department_salary($id)
{
    // Select all columns from the department_tbl table
    $this->db->select('*');
    $this->db->from("department_tbl");
    
    // Filter the query by the department ID
    $this->db->where("id", $id);
    
    // Execute the query
    $qry = $this->db->get();
    
    // Check if any results were returned
    if ($qry->num_rows() > 0)
    {
        // Fetch the result as an array
        $result = $qry->result_array();
        return $result;
    }
    else
    {
        // Return an empty array if no results were found
        return [];
    }
}

    
function get_basic_salary()
{
    // Select the salary from the basic_salary_tbl
    $this->db->select('salary');
    $this->db->from('basic_salary_tbl');
    
    // Execute the query
    $query = $this->db->get();

    // Check if a result is found
    if ($query->num_rows() > 0) {
        // Return the salary value
        return $query->row()->salary;
    } else {
        // Return null or a default value if no salary is found
        return null;
    }
}


public function update_basic_salary($data)
{
    // Update the salary in the basic_salary_tbl
    $this->db->update('basic_salary_tbl', $data);
}



}
