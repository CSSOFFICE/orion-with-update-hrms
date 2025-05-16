<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Payable_model extends CI_Model
{
	public function payable_list($search_supplier = '', $start_date = '', $end_date = '', $search_status = '')
	{
		$this->db->select('p.*, 
        IF(p.purchase_order_id IS NULL OR p.purchase_order_id = 0, p.manual_po_number, po.porder_id) as purchase_order_no,
        s.supplier_name,
        p.after_gst_po_gt as potal');

		$this->db->from('xin_payable p');

		$this->db->join('purchase_order po', 'p.purchase_order_id = po.purchase_order_id', 'left');
		// $this->db->join('purchase_order_item_mapping m', 'po.purchase_order_id = m.porder_id', 'left');

		$this->db->join(
			'xin_suppliers s',
			"(
            (p.purchase_order_id IS NULL OR p.purchase_order_id = 0 AND p.subcon_id = s.supplier_id)
            OR
            (p.purchase_order_id IS NOT NULL AND p.purchase_order_id != 0 AND po.supplier_id = s.supplier_id)
        )",
			'left',
			false
		);

		// Supplier filter
		if ($search_supplier != '') {
			$this->db->group_start();
			$this->db->where('(p.purchase_order_id IS NULL OR p.purchase_order_id = 0)', null, false);
			$this->db->where('p.subcon_id', $search_supplier);
			$this->db->group_end();

			$this->db->or_group_start();
			$this->db->where('p.purchase_order_id IS NOT NULL', null, false);
			$this->db->where('p.purchase_order_id !=', 0);
			$this->db->where('po.supplier_id', $search_supplier);
			$this->db->group_end();
		}

		// Date range filter
		if ($start_date != '') {
			$this->db->where('p.created_datetime >=', date('Y-m-d', strtotime($start_date)));
		}

		if ($end_date != '') {
			$this->db->where('p.created_datetime <=', date('Y-m-d', strtotime($end_date)));
		}

		// Group only when purchase_order_id is set
		if ($search_supplier != '') {
			$this->db->group_start();
			$this->db->where('p.purchase_order_id IS NOT NULL', null, false);
			$this->db->where('p.purchase_order_id !=', 0);
			$this->db->group_end();

			$this->db->group_by('p.purchase_order_id');
		}
		$this->db->group_by('p.invoice_no');

		return $this->db->get();
	}




	public function get_clients()
	{
		$query = $this->db->get('clients');
		return $query->result();
	}
	public function add($data)
	{
		$this->db->insert('xin_payable', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	public function payables_list($id)
	{
		$query = $this->db->select('total,SUM(total) as paid_amount')->where('invoice_no', $id)->from('xin_payable')->get();
		return $query->result();
	}

	public function get_payables_list($id)
	{
		$q = $this->db->where('payable_id', $id)->get('xin_payable')->result();

		$query = $this->db->where('invoice_no', $q[0]->invoice_no)
			->where('total >', 0) // Filter by total greater than 0
			//  ->where('p.invoice_no', $q[0]->invoice_no)

			->from('xin_payable')

			->get();
		return $query->result();
	}

	// public function read_payable($id)
	// {

	//     $query = $this->db->select('p.*, p.total as total_amount,po.porder_id as purchase_order_no')
	//         ->join('purchase_order po', 'p.purchase_order_id = po.purchase_order_id', 'left')
	//         ->where('p.payable_id', $id)
	//         ->group_by('p.invoice_no') // Group by the payable ID to avoid aggregation issues
	//         ->get('xin_payable p');
	//     return $query->result();
	// }

	public function read_payable($id)
	{
		// First get the record to check purchase_order_id
		$payable = $this->db->select('purchase_order_id')->get_where('xin_payable', ['payable_id' => $id])->row();

		$this->db->select('p.*, p.total as total_amount');

		if ($payable && !empty($payable->purchase_order_id) && $payable->purchase_order_id != 0) {
			$this->db->select('po.porder_id as purchase_order_no');
			$this->db->join('purchase_order po', 'p.purchase_order_id = po.purchase_order_id', 'left');
		}

		$query = $this->db
			->from('xin_payable p')
			->where('p.payable_id', $id)
			->group_by('p.invoice_no')
			->get();

		return $query->result();
	}



	public function update($data, $id)
	{
		$this->db->where('payable_id', $id);
		if ($this->db->update('xin_payable', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function delete($id)
	{
		$this->db->where('invoice_no', $id);
		$this->db->delete('xin_payable');
	}
}
