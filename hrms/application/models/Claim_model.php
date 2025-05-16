<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Claim_model extends CI_Model{
    public function claim_list(){
        return $this->db->select('c.*,p.project_title')
                        ->join('projects p','c.project_id=p.project_id','left')
                        ->get('xin_claim c');
		
    }
    public function read_invoice($id){
        $query=$this->db->select('i.invoice_id,i.invoice_no,i.total,i.invoice_due_date,sum(r.paid_amount) as paid_amount')
                        ->join('xin_receivable r','i.invoice_id=r.invoice_id')
                        ->where('i.invoice_id',$id)
                        ->get('finance_invoice i');
        return $query->result();
    }
    public function add($data){
        $this->db->insert('xin_claim', $data);
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
        $query=$this->db->where('invoice_id',$id)
                        ->order_by('receivable_id','desc')
                        ->get('xin_receivable');
        return $query->row();
    }
    public function read_claim($id){
        return $this->db->select('c.*,p.project_title')
                        ->join('projects p','c.project_id=p.project_id','left')
                        ->where('claim_id',$id)
                        ->get('xin_claim c');
		
    }
    public function update($data,$id){
        $this->db->where('claim_id', $id);
		if( $this->db->update('xin_claim',$data)) {
			return true;
		} else {
			return false;
		}	
       
    }
    public function delete($id){
        $this->db->where('claim_id', $id);
    $this->db->delete('xin_claim');
    }	

    public function get_receivable_list($start_date='',$end_date=''){
        $q= $this->db->select('DATE_FORMAT(r.created_datetime,"%Y-%m")  as yearmonth,sum(fi.sub_total) as sales,sum(fi.gst_value) as gst_value,sum(fi.total) as total,fi.gst')
                        ->join('finance_invoice fi','r.invoice_id = fi.invoice_id');
                        if($start_date !=""){
                         $q=   $this->db->where('DATE_FORMAT(r.created_datetime,"%Y-%m") >=', $start_date);
                        }
                        if($end_date !=""){
                         $q=   $this->db->where('DATE_FORMAT(r.created_datetime,"%Y-%m") <=', $end_date);
                        }
                    $q =  $q ->group_by('YEAR(r.created_datetime),MONTH(r.created_datetime)')
                        ->get('xin_receivable r');
                        return $q;
    }
    public function get_credit_list($start_date='',$end_date=''){
        $q= $this->db->select('DATE_FORMAT(r.created_datetime,"%Y-%m")  as yearmonth,sum(fi.sub_total) as sales,sum(fi.gst_value) as gst_value,sum(fi.total) as total,fi.gst')
                        ->join('finance_invoice fi','r.invoice_id = fi.invoice_id');
                        if($start_date !=""){
                            $q=$this->db->where('DATE_FORMAT(r.created_datetime,"%Y-%m") >=', $start_date);
                           }
                           if($end_date !=""){
                            $q=$this->db->where('DATE_FORMAT(r.created_datetime,"%Y-%m") <=', $end_date);
                           }
                $q=$q->group_by('YEAR(r.created_datetime),MONTH(r.created_datetime)')
                        ->get('credit_notes r');
                        return $q;
    }
    public function get_payable_list($start_date='',$end_date=''){
        $q=$this->db->select('DATE_FORMAT(p.created_datetime,"%Y-%m")  as yearmonth,sum(po.total_item_amount) as purchase,sum(po.gst_value) as gst_value,sum(po.total) as total,po.gst')
                        ->join('purchase_order po','p.purchase_order_id = po.purchase_order_id');
                        if($start_date !=""){
                            $q=  $this->db->where('DATE_FORMAT(p.created_datetime,"%Y-%m") >=', $start_date);
                           }
                           if($end_date !=""){
                            $q=  $this->db->where('DATE_FORMAT(p.created_datetime,"%Y-%m") <=', $end_date);
                           }
                   $q=$q ->group_by('YEAR(p.created_datetime),MONTH(p.created_datetime)')
                        ->get('xin_payable p');
                        return $q;
    }
    
} 