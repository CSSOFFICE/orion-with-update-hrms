<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crm_model extends CI_Model
{


    public function add($data)
    {
        $this->db->insert('crm_company_cust', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_items($data)
    {
        $this->db->insert('crm_company_cust_item_map', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_indv($data)
    {
        $this->db->insert('crm_ind_cust', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function indv_cust()
    {
        return $this->db->get('crm_ind_cust')->result();
    }

    public function get_indv_cust()
    {
        return $this->db->get('crm_ind_cust');
    }

    public function read_indv_cust($id)
    {

        return $this->db->select('*')->from('crm_customer')->where('crm_id', $id)->get()->result();
    }

    public function indv_pro_read($id)
    {

        return $this->db->select('*')->from('crm_project')->where('crm_proj_id', $id)->get()->result();
    }
    public function indv_quote_read($id)
    {

        return $this->db->select('*')->from('crm_quotation')->where('crm_q_id', $id)->get()->result();
    }

    public function com_quote_read($id)
    {

        return $this->db->select('*')->from('crm_com_quote')->where('crm_com_qt_id', $id)->get()->result();
    }

    public function com_project_read($id)
    {

        return $this->db->select('*')->from('crm_com_proj')->where('crm_com_proj_id', $id)->get()->result();
    }

    public function read_com_cust($id){
        // crm_customer.company_name,
                                // crm_customer.name,
                                // crm_customer.company_uen,
                                // crm_customer.c_contact_number,
                                // crm_customer.c_email,
                                // crm_customer.c_postal_code,
                                // crm_customer.address,
                                // crm_customer.c_unit_number,
                                // crm_customer.c_credit_limit
        return $this->db->select('crm_customer.*,crm_company_cust_item_map.crm_company_cust_id,crm_company_cust_item_map.p_ic,crm_company_cust_item_map.c_n,crm_company_cust_item_map.e_mail,crm_company_cust_item_map.a_dd')
                        ->from('crm_customer')
                        ->join('crm_company_cust_item_map','crm_customer.crm_id = crm_company_cust_item_map.crm_company_cust_id')
                        ->where('crm_customer.crm_id',$id)
                        ->get()->result();

        // return $this->db->select('*')->from('crm_customer')->where('crm_id', $id)->get()->result();

    }

    public function read_only_com_cust($id)
    {
        return $this->db->select('*')
            ->from('crm_company_cust')
            ->where('crm_c_id', $id)
            ->get()->result();
    }

    public function get_inv_profile($id)
    {
        return $this->db->select('*')->from('crm_customer')->where('crm_id', $id)->get()->result();
    }

    public function get_com_profile($id)
    {
        return $this->db->select('*')->from('crm_customer')->where('crm_id', $id)->get()->result();
    }


    public function company_cust()
    {
        return $this->db->select('*')
            ->from('crm_company_cust')
            // ->join('crm_company_cust_item_map','crm_company_cust.crm_c_id = crm_company_cust_item_map.crm_company_cust_id')
            ->get()->result();
    }

    public function get_company_items($id)
    {
        return $this->db->select('*')
            ->from('crm_company_cust_item_map')
            ->where('crm_company_cust_id', $id)
            ->get()->result();
    }

    public function get_company_cust_id($id)
    {
        return $this->db->select('*')
            ->from('crm_company_cust')
            ->where('crm_c_id', $id)
            ->get()->result();
    }

    public function get_crm_proj($id)
    {
        return $this->db->select('*')
            ->from('crm_project')
            ->where('proj_for', $id)
            ->get()->result();
    }

    public function get_crm_com_proj($id)
    {
        return $this->db->select('*')
            ->from('crm_com_proj')
            ->where('crm_com_proj_for', $id)
            ->get()->result();
    }
    // public function get_crm_quoet($id){
    //      $data['dd'] =$this->db->select('*')
    //     ->from('crm_company_cust')
    //     ->where('crm_c_id', $id)
    //     ->get()->result();
    //      $data['data'] =$this->db->select('*')
    //     ->from('crm_company_cust_item_map')
    //     ->where('crm_company_cust_id', $id)
    //     ->get()->result();
    //     return $data;

    // }

    public function get_crm_quoet($id)
    {
        // return $this->db->select('crm_quotation.*,crm_project.crm_proj_id,crm_project.crm_proj_title,crm_ind_cust.crm_id,crm_ind_cust.customer_name,crm_ind_cust.email')
        //     ->from('crm_quotation')
        //     ->join('crm_project', 'crm_quotation.crm_q_id=crm_project.crm_proj_id')
        //     ->join('crm_ind_cust', 'crm_project.proj_for=crm_ind_cust.crm_id')
        //     ->where('crm_quotation.quote_for', $id)
        //     ->get()
        //     ->result();
        return $this->db->select('*')
        ->from('crm_quotation')
        ->where('quote_for', $id)
        ->get()->result();
    }

    // public function get_crm_com_quoet($id)
    // {
    //     return $this->db->select('crm_com_quote.*,crm_company_cust.name,crm_com_proj.crm_com_proj_id,crm_com_proj.crm_com_proj_title,crm_company_cust.crm_c_id,crm_company_cust.company_name,crm_company_cust.c_email')
    //         ->from('crm_com_quote')
    //         ->join('crm_com_proj', 'crm_com_quote.com_proj_id=crm_com_proj.crm_com_proj_id')
    //         ->join('crm_company_cust', 'crm_com_proj.crm_com_proj_for=crm_company_cust.crm_c_id ')
    //         ->where('crm_com_quote.com_qt_for', $id)
    //         ->get()
    //         ->result();
    // }
    public function get_crm_com_quoet($id)
    {
        return $this->db->select('*')
        ->from('crm_quotation')
        ->where('quote_for', $id)
        ->get()->result();
    }

    public function get_crm_quoet_pdf($id)
    {
        return $this->db->select('crm_quotation.*,crm_project.crm_proj_id,crm_project.crm_proj_des,crm_project.crm_proj_title,crm_ind_cust.crm_id,crm_ind_cust.customer_name,crm_ind_cust.email')
            ->from('crm_quotation')
            ->join('crm_project', 'crm_quotation.indv_proj_id=crm_project.crm_proj_id ')
            ->join('crm_ind_cust', 'crm_quotation.quote_for=crm_ind_cust.crm_id')
            ->where('crm_quotation.crm_q_id', $id)
            ->get()
            ->result();
    }

    public function get_crm_com_quoet_pdf($id)
    {
        return $this->db->select('crm_com_quote.*,crm_company_cust.name,crm_com_proj.crm_com_proj_id,crm_com_proj.crm_com_proj_title,crm_company_cust.crm_c_id,crm_company_cust.company_name,crm_company_cust.c_email,crm_com_proj.crm_com_proj_des')
            ->from('crm_com_quote')
            ->join('crm_com_proj', 'crm_com_quote.com_proj_id=crm_com_proj.crm_com_proj_id')
            ->join('crm_company_cust', 'crm_com_proj.crm_com_proj_for=crm_company_cust.crm_c_id')
            ->where('crm_com_quote.crm_com_qt_id ', $id)
            ->get()
            ->result();
    }

    public function get_company_cust()
    {
        return $this->db->select('crm_company_cust.*,crm_company_cust_item_map.*')
            ->from('crm_company_cust')
            ->join('crm_company_cust_item_map', 'crm_company_cust.crm_c_id = crm_company_cust_item_map.crm_company_cust_id')
            ->get();
    }
    public function get_all_crm_indv_proj($id)
    {
        return $this->db->where('proj_for', $id)->get('crm_project')->result();
    }

    public function get_all_crm_com_proj($id)
    {
        return $this->db->where('crm_com_proj_for', $id)->get('crm_com_proj')->result();
    }

    public function update_indv_data($data, $id)
    {
        $this->db->where('crm_id', $id);
        if ($this->db->update('crm_ind_cust', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_com_data($data, $id)
    {
        $this->db->where('crm_id', $id);
        if ($this->db->update('crm_customer', $data)) {
            return true;
        } else {
            return false;
        }
    }


    public function update($data, $id)
    {
        $this->db->where('crm_q_id', $id);
        if ($this->db->update('crm_quotation', $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_items($data_opt, $id)
    {
        $this->db->where('crm_company_cust_item_id', $id);
        if ($this->db->update('crm_company_cust_item_map', $data_opt)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_indv_project($data, $id)
    {
        $this->db->where('crm_proj_id', $id);
        if ($this->db->update('crm_project', $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_com_project($data, $id)
    {
        $this->db->where('crm_com_proj_id', $id);
        if ($this->db->update('crm_com_proj', $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function indv_cust_pro_read($id)
    {

        return $this->db->select('*')->from('crm_project')->where('proj_for', $id)->get()->result();
    }
    public function com_cust_pro_read($id)
    {

        return $this->db->select('*')->from('crm_com_proj')->where('crm_com_proj_for', $id)->get()->result();
    }
    public function update_indv_quotation($data, $id)
    {
        $this->db->where('crm_q_id', $id);
        if ($this->db->update('crm_quotation', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_com_quotation($data, $id)
    {
        $this->db->where('crm_com_qt_id', $id);
        if ($this->db->update('crm_com_quote', $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function get_term_details($id)
    {
        $query = $this->db->where('term_id', $id)->get('xin_term_condition');
        return $query->result();
    }
}
