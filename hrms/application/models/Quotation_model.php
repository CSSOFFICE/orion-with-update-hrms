<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Quotation_model extends CI_Model {
    public function quotation_list(){
        return $this->db->select('q.*,c.f_name')
                        ->join('clients c','q.customer_id=c.client_id')
                        //->join('projects p','q.project_id=p.project_id')
                        ->get('quotation q');
    }
    public function add($data){
        $this->db->insert('estimates', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
    }
    public function add_task($data){
        $this->db->insert('quotation_task', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
    }
    public function add_subtask($data){
        $this->db->insert('quotation_subtask', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
    }
    public function add_project($data){
        $this->db->insert('projects', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
    }
    public function add_project_task($data){
        $this->db->insert('tasks', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
    }
    public function add_project_subtask($data){
        $this->db->insert('sub_tasks', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
    }
    public function update($data,$id){
		$this->db->where('quotation_id', $id);
		if( $this->db->update('quotation',$data)) {
			return true;
		} else {
			return false;
		}		
	}
    public function update_task($data,$id){
		$this->db->where('quotation_id', $id);
		if( $this->db->update('quotation_task',$data)) {
			return true;
		} else {
			return false;
		}		
	}
    public function update_subtask($data,$id){
		$this->db->where('quotation_id', $id);
		if( $this->db->update('quotation_subtask',$data)) {
			return true;
		} else {
			return false;
		}		
	}
    public function read_quotation($id){
        $query=$this->db->select('q.*,qt.task_id,qt.task,qt.task_description,c.f_name,c.client_phone,c.address')
                        ->join('quotation_task qt','q.quotation_id=qt.quotation_id','left')
                        ->join('clients c','q.customer_id=c.client_id')
                      //  ->join('xin_countries co','s.country_id=co.country_id')
                        //->join('quotation_subtask qst','qt.task_id=qst.task_id')
                        //->join('xin_unit u','qst.unit_id=u.unit_id')
                        ->where('q.quotation_id',$id)
                        ->get('quotation q');
                        return $query->result();

    }
    public function get_tasks($id){
        $query=$this->db->where('qt.quotation_id',$id)
                        ->get('quotation_task qt');
                        return $query->result();
    }
    public function get_subtasks($id){
        $query=$this->db->select('qst.*,u.unit')
                        ->join('xin_unit u','qst.unit_id=u.unit_id')
                        ->where('qst.quotation_id',$id)
                        ->get('quotation_subtask qst');
                        return $query->result();
    }
    public function delete($id){
		$this->db->where('quotation_id', $id);
		if( $this->db->delete('quotation')) {
			return true;
		} else {
			return false;
		}		
	}
    public function delete_task($id){
		$this->db->where('quotation_id', $id);
		if( $this->db->delete('quotation_task')) {
			return true;
		} else {
			return false;
		}		
	}
    public function delete_subtask($id){
		$this->db->where('quotation_id', $id);
		if( $this->db->delete('quotation_subtask')) {
			return true;
		} else {
			return false;
		}		
	}
    public function ajax_customer_address_info($id)
	{
		$query=$this->db->where('client_id',$id)->get('clients');
		return $query->result();
	}
    public function get_clients(){
        $query=$this->db->get('clients');
        return $query->result();
    }
	public function ajax_project_customer_info($id){
		$query=$this->db->select('p.*,c.f_name,c.client_phone,c.address,q.bill_terms')
						->join('estimates q','p.quotation_no=q.quotation_no')
						->join('clients c','c.client_id=p.project_clientid')
						->where('p.project_id',$id)
						->get('projects p');
		return $query->result();
	}
	public function get_crm_customer(){
		return $this->db->select('*')
		         ->from('clients')
				 ->get()
				 ->result();
	}

	public function get_estimate_quotation(){
		return $this->db->select('*')
		         ->from('estimates')
				 ->where('bill_status','accepted')
				 ->get()
				 ->result();
	}
	public function invoice_list(){
		return $this->db->select('i.*,p.project_title,c.f_name,c.client_company_name')
						->join('projects p','i.project_id=p.project_id')
						->join('clients c','i.client_id=c.client_id')
						->get('finance_invoice i');
	}
	public function add_invoice($data){
		$this->db->insert('finance_invoice', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function add_invoice_item($data){
		$this->db->insert('finance_invoice_description_mapping', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function update_invoice($data,$id){
		$this->db->where('invoice_id', $id);
		if( $this->db->update('finance_invoice',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	public function read_invoice($id){
		// q.term_condition_description
		// $query=$this->db->select('i.*,p.project_clientid,p.project_title,p.attn_name,c.*')
		// 				->join('projects p','i.project_id=p.project_id')
		// 				->join('clients as cl','cl.client_id=estimates.bill_clientid')
		// 				->join('clients c','p.project_clientid=c.client_id')
		// 				->where('i.invoice_id ',$id)
		// 				->get('finance_invoice i');
		// return $query->result();
		$query = $this->db->select('i.*, 
		tasks.task_title,i.client_id as cid,
																p.project_clientid, 
																p.project_title, 
																p.attn_name, 
																c.client_id, 
																c.*,estimates.pic_name,
																estimates.pic_email,
																estimates.pic_contact,
																estimates.bill_estimateid,
																estimates.bill_final_amount,
																estimates.quotation_no as qno,
																xin_payment_term.payment_term as terms1
															')
			->from('finance_invoice i')
			->join('projects p', 'i.project_id = p.project_id','left')
			->join('tasks', 'i.task_id = tasks.task_id','left')
			->join('estimates', 'estimates.bill_estimateid  = i.bill_estimateid','left')  // Assuming you have a table named 'estimates'
			->join('clients c', 'i.client_id = c.client_id','left')
			->join('xin_payment_term','i.terms=xin_payment_term.payment_term_id','left')
			->where('i.invoice_id', $id)
			->get();

		return $query->result();

	}



	public function read_invoice_($id){
		$query = $this->db
		->select('*')
		->from('finance_invoice')
		
		->where('invoice_id', $id)
		->get();

	return $query->result();
	}

	public function estimate_($qtn_no){
		$query = $this->db
		->select('bill_estimateid')
		->from('estimates')
		
		->where('quotation_no', $qtn_no)
		->get();

	return $query->result();
	}
	
	public function all_quotation_(){
		$query = $this->db
		->select('*')
		->from('estimates')
		->where('bill_status', 'accepted')
		->get();

	return $query->result();
	}
	public function lineitem_estimateid($estId){
		$query=$this->db->where('lineitemresource_id',$estId)->get('lineitems');
		return $query->result();
	}
	public function read_invoice_item($id){
		$query=$this->db->where('invoice_id',$id)->get('finance_invoice_description_mapping');
		return $query->result();

	}
	public function get_invoice_items($id){
		$query=$this->db->where('invoice_id',$id)->get('finance_invoice_description_mapping');
		return $query->result();

	}
	public function delete_invoice($id){
		$this->db->where('invoice_id', $id);
		if( $this->db->delete('finance_invoice')) {
			return true;
		} else {
			return false;
		}	
	}
	public function delete_invoice_items($id){
		$this->db->where('invoice_id', $id);
		if( $this->db->delete('finance_invoice_description_mapping')) {
			return true;
		} else {
			return false;
		}	
	}
	public function ajax_project_quotation_info($id){
		$query=$this->db->select('q.*')
						->join('quotation q','p.quotation_no=q.quotation_no')
						->where('p.project_id',$id)
						->get('projects p');
						return $query->result();
						

	}
	public function ajax_invoice_info($id){
		$query=$this->db->select('sum(sub_total) as invoice_amount')->where('project_id',$id)->get('finance_invoice');
		return $query->result();
	}
	public function credit_list(){
		return $this->db->select('i.*,p.project_title,c.client_company_name')
						->join('projects p','i.project_id=p.project_id')
						->join('clients c','p.project_clientid=c.client_id')
						->get('credit_notes i');
	}
	public function ajax_project_invoice_info($id){
		$query=$this->db->where('project_id',$id)->get('finance_invoice');
		return $query->result();
	}
	public function ajax_all_invoice_info($id){
		$query=$this->db->select('i.*,t.payment_term')
						->join('xin_payment_term t','i.terms=t.payment_term_id')
						->where('invoice_id',$id)
						->get('finance_invoice i');
		return $query->result();
	}
	public function add_credit_notes($data){
		$this->db->insert('credit_notes', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function add_credit_item($data){
		$this->db->insert('credit_notes_items', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function read_credit($id){
		$query=$this->db->select('i.*,p.project_clientid,p.project_title,p.attn_name,c.client_id,c.client_company_name,c.client_phone,c.address,iv.invoice_no')
						->join('finance_invoice iv','i.invoice_id=iv.invoice_id')
						->join('projects p','i.project_id=p.project_id')
						->join('clients c','p.project_clientid=c.client_id')
						->where('i.credit_id ',$id)
						->get('credit_notes i');
		return $query->result();
	}
	public function read_credit_item($id){
		$query=$this->db->where('credit_note_id',$id)->get('credit_notes_items');
		return $query->result();

	}
	public function update_credit($data,$id){
		$this->db->where('credit_id', $id);
		if( $this->db->update('credit_notes',$data)) {
			
			return true;
		} else {
			return false;
		}		
	}
	public function delete_credit($id){
		$this->db->where('credit_id', $id);
		if( $this->db->delete('credit_notes')) {
			return true;
		} else {
			return false;
		}	
	}
	public function delete_credit_items($id){
		$this->db->where('credit_note_id', $id);
		if( $this->db->delete('credit_notes_items')) {
			return true;
		} else {
			return false;
		}
	}
	public function get_term_details($id){
		$query=$this->db->where('term_id',$id)->get('xin_term_condition');
		return $query->result();
	}	

	public function get_qoutation_no($ids){
		$query=$this->db->select('*')->where('bill_estimateid',$ids)->get('estimates');
		return $query->result();
	}
	public function get_lineitems_data($id){
		$query=$this->db->where('estimates_id',$id)->where('quotation_templates.type', 'row')->get('quotation_templates');
		return $query->result();
	}
	// public function get_invoice_items1($id){
	// 	$query=$this->db->select('*')
	// 	->from('finance_invoice')
	// 	->join('finance_invoice_description_mapping', 'finance_invoice.invoice_id=finance_invoice_description_mapping.invoice_id', 'left')
	// 	->join('product','finance_invoice_description_mapping.item=product.product_id','left')
	// 	->where('finance_invoice.invoice_id', $id)
	// 	->get();
		
	// 	return $query->result();
	// }

	public function get_invoice_items1($id){
		$query=$this->db->select('*,projects.project_sn as contract_sum')
		->from('finance_invoice')
		->join('finance_invoice_description_mapping', 'finance_invoice.invoice_id=finance_invoice_description_mapping.invoice_id', 'left')
		->join('product','finance_invoice_description_mapping.item=product.product_id','left')
		->join('projects','finance_invoice.project_id=projects.project_id','left')
		->where('finance_invoice.invoice_id', $id)
		->get();
		
		return $query->result();

	}


	public function get_invoice_items2(){
		$query=$this->db->select('*')
		->from('finance_invoice')
		->join('finance_invoice_description_mapping', 'finance_invoice.invoice_id=finance_invoice_description_mapping.invoice_id', 'left')
		->join('product','finance_invoice_description_mapping.item=product.product_id','left')
		// ->where('finance_invoice.invoice_id', $id)
		->get();
		
		return $query->result();

	}
	
}