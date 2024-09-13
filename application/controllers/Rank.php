<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rank extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if ( ! $this->session->userdata('logged_in'))
        { 
            redirect(base_url().'login');
        }
        $this->load->model('Rank_model'); // Ensure the model is loaded

    }

    public function index()
    {
        $this->load->view('admin/header');
        $this->load->view('admin/add-rank');
        $this->load->view('admin/footer');
    }

    public function manage_rank()
    {
        $data['content'] = $this->Rank_model->select_ranks();
        $this->load->view('admin/header');
        $this->load->view('admin/manage-rank', $data);
        $this->load->view('admin/footer');
    }

    public function insert()
    {
        $rank = $this->input->post('txtrank');
        $percentage = $this->input->post('txtpercentage');  // Capture percentage from input
    
        // Insert rank and percentage into the model
        $data = $this->Rank_model->insert_rank(array(
            'rank_name' => $rank,
            'percentage' => $percentage  // Include percentage in the data array
        ));
    
        if ($data) {
            $this->session->set_flashdata('success', "New Rank Added Successfully");
        } else {
            $this->session->set_flashdata('error', "Sorry, New Rank Adding Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function update()
    {
        $id = $this->input->post('txtid');
        $rank = $this->input->post('txtrank');
        $percentage = $this->input->post('txtpercentage');  // Capture percentage from input

        // Prepare data for updating
        $data = array(
            'rank_name' => $rank,
            'percentage' => $percentage  // Include percentage in the data array
        );

        // Update rank with new data
        $this->Rank_model->update_rank($data, $id);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('success', "Rank Updated Successfully");
        } else {
            $this->session->set_flashdata('error', "Sorry, Rank Update Failed.");
        }

        redirect(base_url() . "rank/manage_rank");
    }

    function edit($id)
    {
        $data['content'] = $this->Rank_model->select_rank_byID($id);
        $this->load->view('admin/header');
        $this->load->view('admin/edit-rank', $data);
        $this->load->view('admin/footer');
    }

    function delete($id)
    {
        $data = $this->Rank_model->delete_rank($id);
        if ($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('success', "Rank Deleted Successfully"); 
        } else {
            $this->session->set_flashdata('error', "Sorry, Rank Delete Failed.");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
}
