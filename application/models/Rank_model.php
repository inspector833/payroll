<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rank_model extends CI_Model {

    function insert_rank($data)
    {
        $this->db->insert("rank_tbl", $data);
        return $this->db->insert_id();
    }

    function select_ranks()
    {
        $qry = $this->db->get('rank_tbl');
        if($qry->num_rows() > 0)
        {
            return $qry->result_array();
        }
        return [];
    }

    function select_rank_byID($id)
    {
        $this->db->where('id', $id);
        $qry = $this->db->get('rank_tbl');
        if($qry->num_rows() > 0)
        {
            return $qry->result_array();
        }
        return [];
    }

    function delete_rank($id)
    {
        $this->db->where('id', $id);
        $this->db->delete("rank_tbl");
        return $this->db->affected_rows();
    }

    function update_rank($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('rank_tbl', $data);
        return $this->db->affected_rows();
    }
}
