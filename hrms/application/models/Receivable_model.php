<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Receivable_model extends CI_Model{
    function receivable_list(){
        $query=$this->db->select('xin_receivable.*,
                                                                 finance_invoice.invoice_id,
                                                                finance_invoice.total,
                                                                finance_invoice.status,
                                                                finance_invoice.invoice_no,
                                                                p.project_title,
                                                                c.client_company_name,
                                                                c.f_name,
                                                                estimates.bill_date
                                                            ')
                                            // ->from('xin_receivable')
                                            ->join('finance_invoice','xin_receivable.invoice_id=finance_invoice.invoice_id','left')
                                            ->join('projects p','finance_invoice.project_id=p.project_id','left')
                                            ->join('clients c','p.project_clientid=c.client_id','left')
                                            ->join('estimates','finance_invoice.bill_estimateid=estimates.bill_estimateid','left')
                                            ->group_by('xin_receivable.invoice_id');
        $query=$query->get('xin_receivable');
                                            
        return $query;
    }
    // public function invoice_list($start_date='',$end_date='',$search_customer = ''){
        public function invoice_list(){
                $query= $this->db->select('i.*,p.project_title,c.client_company_name,c.f_name,estimates.bill_date')
            ->join('projects p','i.project_id=p.project_id')
            ->join('clients c','p.project_clientid=c.client_id')
            ->join('estimates','i.bill_estimateid=estimates.bill_estimateid');
        // ->join('xin_receivable','i.invoice_id=xin_receivable.invoice_id');
        // if($start_date !=""){
        //     $this->db->where('i.created_datetime >=', $start_date);
        //  }
        //  if($end_date !=""){
        //     $this->db->where('i.created_datetime <=', $end_date);
        //  }
        
        //  if($search_customer !=""){
        //    $this->db->where('i.client_id', $search_customer);

        //  }
        $query=$query->get('finance_invoice i');
        return $query;
		
            // $query=$this->db->select('estimates.*,clients.f_name,clients.last_name')
            //                                     ->from('estimates')
            //                                     ->join('clients','estimates.bill_clientid=clients.client_id')
            //                                     ->where('estimates.bill_status','accepted')
            //                                 ->get();
            // return $query;
        // $query= $this->db->select('i.*,p.project_title,c.client_company_name')
        // ->join('projects p','i.project_id=p.project_id')
        // ->join('clients c','p.project_clientid=c.client_id');
        // if($start_date !=""){
        //     $this->db->where('i.created_datetime >=', $start_date);
        //  }
        //  if($end_date !=""){
        //     $this->db->where('i.created_datetime <=', $end_date);
        //  }
        
        //  if($search_customer !=""){
        //    $this->db->where('i.client_id', $search_customer);

        //  }
        // $query=$query->get('finance_invoice i');
        // return $query;
		
    }
    public function read_invoice($id){
        $query=$this->db->select('i.quotation_no,i.invoice_id,i.invoice_no,i.total,i.invoice_due_date,sum(r.total) as paid_amount,r.invoice_id as inv_pk,r.inv_total,r.gst_num_inv_total,r.after_gst_inv_gt,p.project_title')
                        ->join('xin_receivable r','i.invoice_id=r.invoice_id')
                        ->join('projects p','i.project_id=p.project_id')
                        ->where('i.invoice_id',$id)
                        ->get('finance_invoice i');
        return $query->result();
    }
    public function add($data){
        $this->db->insert('xin_receivable', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
    }
    public function get_receivable($id){
        $query=$this->db->where('invoice_id',$id)->get('xin_receivable');
        return $query->result();
    }
    public function get_receivable_status($id){
        $query=$this->db->where('receivable_id',$id)
                        ->order_by('receivable_id','desc')
                        ->get('xin_receivable');
        return $query->row();
    }
    public function get_customers(){
        $query=$this->db->get('clients');
        return $query->result();
    }
} 