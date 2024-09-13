<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salary extends CI_Controller {

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
        $data['departments']=$this->Department_model->select_departments();
        $this->load->view('admin/header');
        $this->load->view('admin/add-salary',$data);
        $this->load->view('admin/footer');
    }

    public function invoice($id)
    {
        $data['content']=$this->Salary_model->select_salary_byID($id);
        $this->load->view('admin/header');
        $this->load->view('admin/salary-invoice',$data);
        $this->load->view('admin/footer');
    }

    public function invoicestaff($id)
    {
        $data['content']=$this->Salary_model->select_salary_byID($id);
        $this->load->view('staff/header');
        $this->load->view('staff/salaryinvoice',$data);
        $this->load->view('staff/footer');
    }

    public function invoice_print($id)
    {
        $data['content']=$this->Salary_model->select_salary_byID($id);
        $this->load->view('admin/invoice-print',$data);
    }

    public function manage()
    {
        $data['content']=$this->Salary_model->select_salary();
        $this->load->view('admin/header');
        $this->load->view('admin/manage-salary',$data);
        $this->load->view('admin/footer');
    }

    public function set()
    {
        $this->load->model('Salary_model');
    
    // Retrieve the salary from the basic_salary_tbl
    $data['salary'] = $this->Salary_model->get_basic_salary();
    $this->load->view('admin/header');
    // Load the view and pass the salary data
    $this->load->view('admin/set-salary', $data);
    $this->load->view('admin/footer');
    
    }

    public function view()
{
    $staff = $this->session->userdata('userid');
    $data['content'] = $this->Salary_model->select_salary_byStaffID($staff);
    $this->load->view('staff/header');
    $this->load->view('staff/view-salary', $data);
    $this->load->view('staff/footer');
}


    // public function insert()
    // {
    //     $id=$this->input->post('txtid');
    //     $basic=$this->input->post('txtbasic');
    //     $allowance=$this->input->post('txtallowance');
    //     $total=$this->input->post('txttotal');
    //     $added=$this->session->userdata('userid');

    //     $salary=array();
    //     for ($i=0; $i < count($id); $i++)
    //     { 
    //         if($total[$i]>0)
    //         {
    //             $data=$this->Salary_model->insert_salary(array('staff_id' => $id[$i],
    //                 'basic_salary' => $basic[$i],
    //                 'allowance' => $allowance[$i],
    //                 'total' => $total[$i],
    //                 'added_by' => $added)
    //             );
    //         }
    //     }
        
    //     if($this->db->affected_rows() > 0)
    //     {
    //         $this->session->set_flashdata('success', "Salary Added Succesfully"); 
    //     }else{
    //         $this->session->set_flashdata('error', "Sorry, Salary Adding Failed.");
    //     }
    //     redirect($_SERVER['HTTP_REFERER']);
    // }

    public function insert()
{
    $id = $this->input->post('txtid');
    $basic = $this->input->post('txtbasic');
    $allowance = $this->input->post('txtallowance');
    $total = $this->input->post('txttotal');
    $added = $this->session->userdata('userid');

    for ($i = 0; $i < count($id); $i++) {
        if ($total[$i] > 0) {
            // Check if the staff already has a salary record
            $existing_salary = $this->Salary_model->get_salary_by_staff_id($id[$i]);

            if ($existing_salary) {
                // If the salary exists, update it
                $data = array(
                    'basic_salary' => $basic[$i],
                    'allowance' => $allowance[$i],
                    'total' => $total[$i],
                    'added_by' => $added,
                    'updated_on' => date('Y-m-d H:i:s')
                );
                $this->Salary_model->update_salary($id[$i], $data);
            } else {
                // If the salary doesn't exist, insert a new record
                $data = array(
                    'staff_id' => $id[$i],
                    'basic_salary' => $basic[$i],
                    'allowance' => $allowance[$i],
                    'total' => $total[$i],
                    'added_by' => $added,
                    'added_on' => date('Y-m-d H:i:s')
                );
                $this->Salary_model->insert_salary($data);
            }
        }
    }

    if ($this->db->affected_rows() > 0) {
        $this->session->set_flashdata('success', "Salary updated/added successfully");
    } else {
        $this->session->set_flashdata('error', "Sorry, salary update/add failed.");
    }
    redirect($_SERVER['HTTP_REFERER']);
}


    public function update()
    {
        $id=$this->input->post('txtid');
        $department=$this->input->post('txtdepartment');
        $data=$this->Department_model->update_department(array('department_name'=>$department),$id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Salary Updated Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Salary Update Failed.");
        }
        redirect(base_url()."department/manage_department");
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
        $data=$this->Salary_model->delete_salary($id);
        if($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Salary Deleted Succesfully"); 
        }else{
            $this->session->set_flashdata('error', "Sorry, Salary Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }


    public function get_salary_list()
    {
        $dept = $_POST['dept'];
        $data=$this->Staff_model->select_staff_byDept($dept);
        $salary_data = $this->Salary_model->get_department_salary($dept);

        if(isset($data)){
            print '<div class="box-body">
            <div class="col-md-6">
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
            <thead>
                  <tr>
                    <th>Staff</th>
                     <th> </th>
                    <th> </th>
                    <th>Allowance (GHS))</th>
                  </tr>
                  </thead>
                  <tbody>';

            foreach($data as $d)
            {
                $allowance = isset($d["allowance"]) ? $d["allowance"] : ''; // Default to empty if allowance is not set
                $first_name = isset($d["first_name"]) ? $d["first_name"] : '';
                $middle_name = isset($d["middle_name"]) ? $d["middle_name"] : '';
                $last_name = isset($d["last_name"]) ? $d["last_name"] : '';
                $staff_name = isset($d["staff_name"]) ? $d["staff_name"] : '';
            
                // Concatenate names if `first_name` is present
                $full_name = !empty($first_name) 
        ? htmlspecialchars($first_name . 
            (!empty($middle_name) ? ' ' . $middle_name : '') . 
            (!empty($last_name) ? ' ' . $last_name : '')
          )
        : $staff_name;
                print '<tr>
                <td>' . $full_name . '</td>
                <td>
                <input type="hidden" name="txtsalary[]" class="form-control expenses" readonly value="'.$salary_data[0]['salary'].'" >
            </td>
                <td><input type="hidden" name="txtid[]" value="'.$d["id"].'">
                    <input type="hidden" name="txtbasic[]" class="form-control expenses">
                </td>
                <td><input type="text" name="txtallowance[]" value="' . htmlspecialchars($allowance) . '" class="form-control expenses"></td>
                <td><input type="hidden" id="total" name="txttotal[]" class="form-control"></td>
                </tr>';
            }
            print '</tbody>
            </table>
            </div>
            </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div>';
            // print '<div class="col-md-12">
            //       <div class="form-group">
            //         <label for="exampleInputPassword1">Department Name</label>
            //         <select class="form-control" name="slcdepartment" onchange="getstaff(this.value)">
            //           <option value="">Select</option>
                        
            //         </select>
            //       </div>
            //     </div>';
        }
        
        

    }


    public function update_salary()
{
    // Load the Salary_model
    $this->load->model('Salary_model');

    // Check if form is submitted
    if ($this->input->post()) {
        // Get the new salary from the form input
        $new_salary = $this->input->post('new_salary');

        // Prepare the data array to update
        $data = array(
            'salary' => $new_salary
        );

        // Update the salary in the database
        if($this->Salary_model->update_basic_salary($data)) {
            // Set a success message
            $this->session->set_flashdata('success', 'Salary updated successfully!');
        } else {
            // Set an error message if update fails
            $this->session->set_flashdata('error', 'Failed to update salary!');
        }

        // Redirect back to the form or another page
        redirect('Salary/set');
    }
}

    
}
