<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Purchase_model extends CI_Model
{
	public function add($data)
	{
		$this->db->insert('purchase_requistion', $data);

		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function add_order_image($data)
	{
		$this->db->insert('purchase_order_images', $data);

		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	////////INV Sequence//////////
	public function get_current_inv_sequence($currentMonth)
	{
		$this->db->where('year_month', $currentMonth);
		$query = $this->db->get('inv_sequence');
		return $query->row();
	}

	public function update_inv_sequence($currentMonth, $new_sequence)
	{
		$this->db->where('year_month', $currentMonth);
		$this->db->update('inv_sequence', ['sequence' => $new_sequence]);
	}

	public function insert_inv_sequence($currentMonth, $new_sequence)
	{
		$this->db->insert('inv_sequence', ['year_month' => $currentMonth, 'sequence' => $new_sequence]);
	}
	////////INV Sequence//////////

	////////PR Sequence//////////
	public function get_current_pr_sequence($currentMonth)
	{
		$this->db->where('year_month', $currentMonth);
		$query = $this->db->get('pr_sequence');
		return $query->row();
	}

	public function update_pr_sequence($currentMonth, $new_sequence)
	{
		$this->db->where('year_month', $currentMonth);
		$this->db->update('pr_sequence', ['sequence' => $new_sequence]);
	}

	public function insert_pr_sequence($currentMonth, $new_sequence)
	{
		$this->db->insert('pr_sequence', ['year_month' => $currentMonth, 'sequence' => $new_sequence]);
	}
	////////PR Sequence//////////



	////////PO Sequence//////////
	public function get_current_sequence($currentMonth)
	{
		$this->db->where('year_month', $currentMonth);
		$query = $this->db->get('po_sequence');
		return $query->row();
	}

	public function update_sequence($currentMonth, $new_sequence)
	{
		$this->db->where('year_month', $currentMonth);
		$this->db->update('po_sequence', ['sequence' => $new_sequence]);
	}

	public function insert_sequence($currentMonth, $new_sequence)
	{
		$this->db->insert('po_sequence', ['year_month' => $currentMonth, 'sequence' => $new_sequence]);
	}
	////////PO Sequence//////////


	////////GRN Sequence//////////
	public function get_grn_sequence($year_month)
	{
		return $this->db->select('sequence')
			->from('grn_sequence')
			->where('year_month', $year_month)
			->get()
			->row();
	}

	public function update_grn_sequence($year_month, $new_sequence)
	{
		$this->db->update('grn_sequence', ['sequence' => $new_sequence], ['year_month' => $year_month]);
	}

	public function insert_grn_sequence($year_month, $new_sequence)
	{
		$this->db->insert('grn_sequence', ['year_month' => $year_month, 'sequence' => $new_sequence]);
	}
	////////GRN Sequence//////////

	public function add_items($data)
	{
		$this->db->insert('purchase_requistion_item_mapping', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function purchase_requistion_list()
	{
		return $this->db->select('pr.*,p.project_title,c.f_name,c.client_company_name,p.project_title')
			->join('projects p', 'p.project_id=pr.project_id')
			// ->join('xin_suppliers s','purchase_requistion_item_mapping.supplier_id=s.supplier_id')
			->join('clients c', 'pr.customer_id=c.client_id')
			->get('purchase_requistion pr');
	}

	////////////NEW 3.11.23///////
	public function purchase_requistion_pending_list()
	{
		$query = $this->db->select('pr.*,p.project_title,s.supplier_name,c.client_company_name')
			->join('projects p', 'p.project_id=pr.project_id')
			->join('xin_suppliers s', 'pr.supplier_id=s.supplier_id')
			->join('clients c', 'pr.customer_id=c.client_id')
			->where('pr.status', 'Waiting for Confirmation')
			->get('purchase_requistion pr');
		if ($query) {
			return $query->result_array();
		}
	}
	///////////END 3.11.23/////////

	public function read_purchase_requistion($id)
	{
		return $this->db->select('pr.*, pr.site_address as address1,tasks.task_title as description,
		customer.first_name as customer_first_name, customer.last_name as customer_last_name,
		supervisor.first_name as supervisor_first_name, supervisor.last_name as supervisor_last_name,
		subcontractor.supplier_name as subcontractor_first_name,
		approver.first_name as approver_first_name, approver.last_name as approver_last_name,
		approver.signature as approver_signature,
		p.project_title, pr.status as pstatus')
			->join('xin_employees customer', 'pr.customer_id = customer.user_id', 'left')
			->join('xin_employees supervisor', 'pr.supervisor = supervisor.user_id', 'left')
			->join('xin_suppliers subcontractor', 'pr.sub_contractor = subcontractor.supplier_id AND subcontractor.subcon_supplier = "Yes"', 'left')
			->join('xin_employees approver', 'pr.apr_by = approver.user_id', 'left')
			->join('projects p', 'p.project_id = pr.project_id', 'left')
			->join('tasks', 'pr.task=tasks.task_id','left')				

			->where('pr.purchase_requistion_id', $id)
			->get('purchase_requistion pr')
			->result();

		// $query=$this->db->select('pr.*,
		// 														p.project_title,
		// 														purchase_requistion_item_mapping.supplier_id,
		// 														s.supplier_name,
		// 														s.address as supplier_address,
		// 														s.phone as supplier_phone,
		// 														s.pincode as supplier_pincode,
		// 														c.first_name,
		// 														c.last_name
		// 														')		
		// 				->join('projects p','p.project_id=pr.project_id')
		// 				// ->join('quotation q','p.quotation_no=q.quotation_no')
		// 				->join('purchase_requistion_item_mapping','pr.purchase_requistion_id=purchase_requistion_item_mapping.purchase_requistion_id')
		// 				->join('xin_suppliers s','purchase_requistion_item_mapping.supplier_id=s.supplier_id')
		// 				->join('users c','pr.customer_id=c.id')
		// 				// ->join('xin_countries co','s.country_id=co.country_id')
		// 				->where('pr.purchase_requistion_id',$id)
		// 				->get('purchase_requistion pr');
		// 				if ($query->num_rows() > 0) {
		// 					return $query->result();
		// 				} else {
		// 					return false;
		// 					print_r($this->db->error());
		// 				}
	}
	public function get_preq_inv_data($id)
	{
		// $data=$this->db->select('purchase_requistion.*')
		// 					->from('purchase_requistion')
		// 					->join('purchase_requistion_item_mapping','purchase_requistion.purchase_requistion_id =purchase_requistion_item_mapping.purchase_requistion_id')
		// 				    ->join('product','purchase_requistion_item_mapping.product_id=product.product_id')
		// 					->join('clients','purchase_requistion.customer_id=clients.client_id')
		// 					->get();
		// return $data->result();


		// $data=$this->db->select('purchase_requistion.*,						
		// 				projects.project_id,projects.project_title,projects.project_description,
		// 				users.first_name,users.last_name,users.email

		// 				')
		// 		->from('purchase_requistion')
		// 		->join('purchase_requistion_item_mapping','purchase_requistion.purchase_requistion_id =purchase_requistion_item_mapping.purchase_requistion_id')
		// 		->join('product','purchase_requistion_item_mapping.product_id=product.product_id')
		// 		->join('projects','purchase_requistion.project_id=projects.project_id')
		// 		->join('users','purchase_requistion.customer_id=users.id')
		// 		->where('purchase_requistion.purchase_requistion_id',$id)
		// 		->get();
		$data = $this->db->select('purchase_requistion.*,
		purchase_requistion_item_mapping.description,
		projects.project_id,projects.project_title,projects.project_description,
		purchase_requistion_item_mapping.qty,
		xin_employees.first_name,
		xin_employees.last_name,
		xin_employees.email
	')
			->from('purchase_requistion')
			->join('purchase_requistion_item_mapping', 'purchase_requistion.purchase_requistion_id =purchase_requistion_item_mapping.purchase_requistion_id', 'left')
			->join('product', 'purchase_requistion_item_mapping.product_id=product.product_id', 'left')
			->join('xin_employees', 'purchase_requistion.customer_id=xin_employees.user_id', 'left')
			->join('projects', 'purchase_requistion.project_id=projects.project_id', 'left') // Use left join here
			->where('purchase_requistion.purchase_requistion_id', $id)
			->get();
		return $data->result();
	}

	function read_get_po($id)
	{
		$query = $this->db->select('purchase_order.*,
													xin_employees.first_name,
													xin_employees.last_name,
													projects.project_title,
													xin_suppliers.supplier_id,
													xin_suppliers.supplier_name,
																									
													purchase_order_item_mapping.porder_id as pur_id, 
													purchase_order_item_mapping.prd_uom_from_prq, 
													purchase_order_item_mapping.prd_id, 
													purchase_order_item_mapping.unit, 
													purchase_order_item_mapping.type, 
													purchase_order_item_mapping.b_img, 
													purchase_order_item_mapping.a_img, 
													purchase_order_item_mapping.img_description, 
													purchase_order_item_mapping.description, 
													purchase_order_item_mapping.prd_price, 
													purchase_order_item_mapping.prd_qtn, 
													purchase_order_item_mapping.prd_color, 
													purchase_order_item_mapping.prd_color_name, 
													purchase_order_item_mapping.supplier_id
													')
			->from('purchase_order')
			->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id', 'left')
			->join('xin_suppliers', 'purchase_order_item_mapping.supplier_id=xin_suppliers.supplier_id', 'left')
			->join('xin_employees', 'purchase_order.po_for=xin_employees.user_id', 'left')
			// ->join('xin_supplier_billing', 'xin_suppliers.supplier_id=xin_supplier_billing.supplier_id','left')
			->join('projects', 'purchase_order.project_id=projects.project_id', 'left')
			->where('purchase_order.purchase_order_id', $id)
			->get();
		if ($query) {
			// return $this->db->last_query();
			return $query->result();
		} else {
			return FALSE;
		}

		// $query=	$this->db->select('purchase_requistion.*,users.first_name,users.last_name,conf_purchase_req.conf_sup_id,p.project_title,product.product_name,xin_suppliers.supplier_name')
		// 						->from('purchase_requistion')
		// 						->join('conf_purchase_req','purchase_requistion.purchase_requistion_id=conf_purchase_req.conf_pr_id')
		// 						->join('xin_suppliers','conf_purchase_req.conf_sup_id=xin_suppliers.supplier_id')
		// 						->join('projects p','p.project_id=purchase_requistion.project_id')
		// 						->join('product','conf_purchase_req.conf_item_id=product.product_id')
		// 						->join('users','purchase_requistion.customer_id=users.id')
		// 						->where('conf_purchase_req.conf_id',$id)
		// 						->get();
		// 						if($query){
		// 							// return $this->db->last_query();
		// 							return $query->result();	

		// 						}else{
		// 							return FALSE;
		// 						}
	}

	function read_get_po1($id)
	{
		// xin_suppliers.emps_id,             
		// xin_suppliers.pincode as supplier_pincode,
		$query = $this->db->select(
			'purchase_order.*,
                            purchase_order.created_datetime as podate,
                            xin_employees.first_name,
                            xin_employees.last_name,
                            xin_employees.contact_no as employee_phone,
                            projects.project_title,
                            product.product_name,
                            purchase_order_item_mapping.supplier_id,
                            purchase_order_item_mapping.prd_price,
                            purchase_order_item_mapping.prd_qtn,
                            purchase_order_item_mapping.prd_total,
                            purchase_order_item_mapping.sup_ref,
                            purchase_order_item_mapping.terms,
                            xin_suppliers.supplier_name,
                            xin_supplier_billing.contact as supplier_phone,
                            xin_supplier_billing.address AS supplier_address,
                            xin_supplier_billing.email as email_address,
                            xin_supplier_billing.pic as supplier_contact_person,
							tasks.task_title as description
							,'
		)
			->from('purchase_order')
			->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id = purchase_order_item_mapping.porder_id', 'left')
			->join('product', 'purchase_order_item_mapping.prd_id = product.product_id', 'left')
			->join('xin_suppliers', 'purchase_order_item_mapping.supplier_id = xin_suppliers.supplier_id', 'left')
			->join('xin_supplier_billing', 'purchase_order.sup_bill_id = xin_supplier_billing.bill_id', 'left')
			->join('projects', 'purchase_order.project_id = projects.project_id', 'left')
			->join('xin_employees', 'purchase_order.po_for = xin_employees.user_id', 'left')
			->join('tasks', 'purchase_order.task=tasks.task_id','left')				

			->where('purchase_order.purchase_order_id', $id)
			->get();

		// $this->db->select('purchase_requistion.*,
		// 														purchase_requistion.created_datetime as podate,
		// 														purchase_order_item_mapping.*,
		// 														p.project_sn,
		// 														users.first_name,
		// 														users.last_name,
		// 														users.phone,
		// 														conf_purchase_req.conf_id,
		// 														p.project_title,
		// 														product.product_name,
		// 														xin_suppliers.supplier_name,
		// 														xin_suppliers.phone as supplier_phone,
		// 														xin_suppliers.address as supplier_address,
		// 														xin_suppliers.pincode as supplier_pincode
		// 														')
		// 						->from('purchase_requistion')
		// 						->join('conf_purchase_req','purchase_requistion.purchase_requistion_id=conf_purchase_req.conf_pr_id')
		// 						->join('xin_suppliers','conf_purchase_req.conf_sup_id=xin_suppliers.supplier_id')
		// 						->join('purchase_order_item_mapping','conf_purchase_req.conf_item_id=purchase_order_item_mapping.prd_id')
		// 						->join('projects p','p.project_id=purchase_requistion.project_id')
		// 						->join('product','conf_purchase_req.conf_item_id=product.product_id')
		// 						->join('users','purchase_requistion.customer_id=users.id')
		// 						->where('conf_purchase_req.conf_id',$id)
		// 						->get();
		if ($query) {
			// return $this->db->last_query();
			return $query->result();
		} else {
			return FALSE;
		}
	}



	public function read_purchase_order($id)
	{
		$query = $this->db->select('po.*,
								  purchase_order_item_mapping.supplier_id,
								  purchase_order_item_mapping.porder_id,
								  p.project_title,
								  s.supplier_name,
								  s.address as supplier_address,
								  s.phone as supplier_phone,
								  s.pincode as supplier_pincode,

								  c.client_company_name,
								  c.client_phone,

								  u.first_name,u.last_name,u.contact_no as phone,
								
								')

			->join('projects p', 'p.project_id=po.project_id')
			// ->join('quotation q','p.quotation_no=q.quotation_no')
			->join('purchase_order_item_mapping', 'po.purchase_order_id=purchase_order_item_mapping.porder_id')
			->join('purchase_requistion', 'po.preq_id=purchase_requistion.purchase_requistion_id')
			->join('conf_purchase_req', 'purchase_requistion.purchase_requistion_id=conf_purchase_req.conf_pr_id')
			->join('xin_suppliers s', 'purchase_order_item_mapping.supplier_id=s.supplier_id')
			->join('clients c', 'purchase_requistion.customer_id=c.client_id')
			// ->join('xin_countries co','s.country_id=co.country_id')
			->join('xin_employees u', 'po.created_by=u.user_id')
			->where('conf_purchase_req.conf_id', $id)
			->get('purchase_order po');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	function add_grn_data($data)
	{
		$this->db->insert('grn_tbl', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function read_purchase_order1($id)
	{
		$query = $this->db->select('po.*,
																purchase_order_item_mapping.supplier_id,
																purchase_order_item_mapping.porder_id,
																SUM(purchase_order_item_mapping.prd_total) as total
																')

			// ->join('projects p','p.project_id=po.project_id')
			// ->join('quotation q','p.quotation_no=q.quotation_no')
			->join('purchase_order_item_mapping', 'po.purchase_order_id=purchase_order_item_mapping.porder_id')
			// ->join('xin_suppliers s','purchase_order_item_mapping.supplier_id=s.supplier_id')
			// ->join('clients c','po.customer_id=c.client_id')
			// ->join('xin_countries co','s.country_id=co.country_id')
			// ->join('users u','po.created_by=u.id')
			->where('po.purchase_order_id', $id)
			->get('purchase_order po');

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_purchase_order_items($id)
	{
		$query = $this->db->select('po.*,s.supplier_name,s.supplier_number,s.address,s.pincode,s.phone,s.fax1,s.email_id')
			->join('xin_suppliers s', 'po.supplier_id=s.supplier_id')
			->where('po.porder_id', $id)->get('purchase_order_item_mapping po');

		return $query->result();
	}
	public function get_purchase_requistion_items($id)
	{
		$query = $this->db->where('purchase_requistion_id', $id)->get('purchase_requistion_item_mapping');
		return $query->result();
	}
	public function update($data, $id)
	{
		$this->db->where('purchase_requistion_id', $id);
		if ($this->db->update('purchase_requistion', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function update_order($data, $id)
	{
		$this->db->where('purchase_order_id', $id);
		if ($this->db->update('purchase_order', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_item_record($id)
	{

		$this->db->where('purchase_requistion_id', $id);
		$this->db->delete('purchase_requistion_item_mapping');
	}
	public function delete_order_item_record($id)
	{
		$this->db->where('porder_id', $id);
		$this->db->delete('purchase_order_item_mapping');
	}
	public function delete_order_record($id)
	{
		$this->db->where('purchase_order_id', $id);
		$this->db->delete('purchase_order');
	}
	public function delete_record($id)
	{
		$this->db->where('purchase_requistion_id', $id);
		$this->db->delete('purchase_requistion');
	}
	public function ajax_supplier_address_info($id)
	{
		$query = $this->db->where('supplier_id', $id)->get('xin_suppliers');
		return $query->result();
	}
	public function purchase_order_list()
	{
		// s.supplier_name,c.client_company_name,
		// purchase_order_item_mapping.supplier_id,purchase_order_item_mapping.purchase_order_id'
		// return $this->db->select('po.*,p.project_title,users.first_name,users.last_name,p.project_title')
		// 				->join('projects p','p.project_id=po.project_id')
		// 				// ->join('purchase_order_item_mapping','po.purchase_order_id=purchase_order_item_mapping.purchase_order_id')
		// 				// ->join('xin_suppliers s','purchase_order_item_mapping.supplier_id=s.supplier_id')
		// 				->join('users','po.customer_id=users.id')
		// 				->get('purchase_order po');

		return $this->db
			->group_by('conf_purchase_req.conf_sup_id,purchase_requistion.modified_datetime')
			->select('*,purchase_order.purchase_order_id,purchase_requistion.created_datetime as cdate')
			->from('conf_purchase_req')
			->join('purchase_requistion', 'conf_purchase_req.conf_pr_id=purchase_requistion.purchase_requistion_id')
			->join('purchase_order', 'conf_purchase_req.conf_pr_id=purchase_order.preq_id')
			// ->join('products','conf_purchase_reqconf_item_id=products.project_id')
			// ->order_by('conf_purchase_req.conf_sup_id')
			// ->join('users','purchase_requistion.customer_id=users.id')
			->get();
	}
	public function add_order($data)
	{
		$this->db->insert('purchase_order', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function add_order_items($data)
	{
		$this->db->insert('purchase_order_item_mapping', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function update_supplier_product_price($data, $id, $p_id)
	{
		$this->db->where('supplier_id', $id);
		$this->db->where('supplier_item_name', $p_id);
		if ($this->db->update('xin_supplier_item_mapping', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function get_purchase_order_item($id)
	{
		$query = $this->db->select('po.*,p.product_name')
			->join('product p', 'po.product_id=p.product_id')
			->where('po.purchase_order_id', $id)
			->get('purchase_order_item_mapping po');
		return $query->result();
	}
	public function get_all_products()
	{
		$query = $this->db->get('product');
		return $query->result();
	}
	public function get_term_details($id)
	{
		$query = $this->db->select('q.term_condition_description')
			->join('quotation q', 'p.quotation_no=q.quotation_no')
			->where('p.project_id', $id)
			->get('projects p');
		return $query->result();
	}
	public function update_status($data, $id)
	{
		$this->db->where('purchase_requistion_id', $id);
		if ($this->db->update('purchase_requistion', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function get_project_data($id)
	{
		$query = $this->db->where('project_id', $id)
			->get('projects');
		return $query->result();
	}
	public function get_all_items($id)
	{
		$query = $this->db->where('porder_id', $id)
			->get('purchase_order_item_mapping');
		return $query->result();
	}
	public function get_product_details($id)
	{
		$query = $this->db->select('p.product_id,p.product_name,sm.supplier_item_description as description,p.std_uom,p.base_uom,sm.supplier_item_price as cost_price')
			->join('xin_supplier_item_mapping sm', 'p.product_id=sm.supplier_item_name')
			->where('p.product_id', $id)
			->get('product p');
		return $query->result();
	}
	public function get_all_images($id)
	{
		$query = $this->db->where('purchase_order_id', $id)
			->get('purchase_order_images');
		return $query->result();
	}

	public function delete_image($id)
	{

		$this->db->where('purchase_order_id', $id);
		$this->db->delete('purchase_order_images');
	}

	public function get_order_detail($id)
	{
		$query = $this->db->select('po.*,p.project_title')
			->join('projects p', 'po.project_id = p.project_id')

			->where('po.purchase_order_id', $id)
			->get('purchase_order po');
		return $query->result();
	}

	public function get_employee_detail($id)
	{
		$query = $this->db->select('e.first_name,e.last_name,c.name,c.email,c.contact_number,c.address_1,c.address_2,c.city,c.state,c.zipcode,c.country,c.registration_no,c.government_tax')
			->join('xin_companies c', 'e.company_id=c.company_id')
			->where('e.user_id', $id)
			->get('xin_employees e');
		return $query->result();
	}

	public function get_project_address($id)
	{
		$query = $this->db->select('c.*,p.supervisor,p.project_id as pr_id,p.project_address')
			->join('clients c', 'p.project_clientid =c.client_id', 'left')
			// ->join('projects_manager pm', 'p.project_id=pm.projectsmanager_projectid', 'left')
			// ->join('estimates', 'p.project_id=estimates.bill_projectid', 'left')
			// ->join('quotation_templates', 'p.quotation_no =quotation_templates.quotation_no', 'left')
			->where('p.project_id', $id)
			->get('projects p');
		return $query->result();
	}

	public function get_quotationdata_from_project($proj_id,$milestone_id){
		// $query = $this->db->select('quotation_templates.*')			
		// 	->join('estimates', 'p.project_id=estimates.bill_projectid', 'left')
		// 	->join('quotation_templates', 'p.quotation_no =quotation_templates.quotation_no', 'left')
		// 	->group_by('template_id')
		// 	->where('p.project_id', $proj_id)
		// 	->get('projects p');
		// return $query->result();


		$query = $this->db->select('tasks.task_id,tasks.task_title')
		->from('tasks')
		->join('projects', 'projects.project_id = tasks.task_projectid ', 'left')
		->join('milestone_categories', 'tasks.task_cat_id = milestone_categories.milestonecategory_id ', 'left')
		->where('tasks.task_projectid', $proj_id)
		->where('tasks.task_status !=', 'completed')
		->where('tasks.task_cat_id', $milestone_id)
		->get();
		
	return $query->result();
	
	}
	
}
