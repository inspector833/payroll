<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
    }

    public function index()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/add-department');
        $this->load->view('admin/footer');
    }

    public function manage_department()
    {
        $data['content']=$this->Department_model->select_departments();
        $this->load->view('admin/header');
        $this->load->view('admin/manage-department',$data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {
        $department = $this->input->post('txtdepartment');
        $salary = $this->input->post('txtsalary');  // Add this line to capture salary
    
        // Insert department and salary into the model
        $data = $this->Department_model->insert_department(array(
            'department_name' => $department,
            'salary' => $salary  // Include salary in the data array
        ));
    
        if ($data) {
            $this->session->set_flashdata('success', "New Department Added Successfully");
        } else {
            $this->session->set_flashdata('error', "Sorry, New Department Adding Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function update()
{
    $id = $this->input->post('txtid');
    $department = $this->input->post('txtdepartment');
    $salary = $this->input->post('txtsalary');  // Capture salary from input

    // Prepare data for updating
    $data = array(
        'department_name' => $department,
        'salary' => $salary  // Include salary in the data array
    );

    // Update department with new data
    $this->Department_model->update_department($data, $id);

    if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', "Department Updated Successfully");
    } else {
        $this->session->set_flashdata('error', "Sorry, Department Update Failed.");
    }

    redirect(base_url() . "department/manage_department");
}



    function edit($id)
    {
        $data['content']=$this->Department_model->select_department_byID($id);
        $this->load->view('admin/header');
        $this->load->view('admin/edit-department',$data);
        $this->load->view('admin/footer');
    }


    function delete($id)
    {
        $data=$this->Department_model->delete_department($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Department Deleted Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Department Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }



}
