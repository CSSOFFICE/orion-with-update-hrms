<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	public function get_supplier()
	{
		return $this->db->get("xin_suppliers");
	}
	public function get_company_supplier($id)
	{

		$sql = 'SELECT * FROM xin_suppliers WHERE company_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);

		return $query;
	}
	public function add($data)
	{
		$this->db->insert('xin_suppliers', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	public function read_supplier_information($id)
	{
		$this->db->select('*');
		$this->db->from('xin_suppliers');
		// $this->db->join('xin_employees', 'xin_suppliers.emps_id = xin_employees.user_id', 'left'); 
		$this->db->where('xin_suppliers.supplier_id', $id); // Filter by supplier_id

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return null;
		}
	}
	public function update_record($data, $id)
	{
		$this->db->where('supplier_id', $id);
		if ($this->db->update('xin_suppliers', $data)) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_record($id)
	{
		$this->db->where('supplier_id ', $id);
		$this->db->delete('xin_suppliers');
	}

	public function customer_supplier_data()
	{
		$combined = [];

		// Modified SQL with dual PO handling
		$sql = "
						SELECT 
							s.supplier_id, 
							s.supplier_name, 
							s.phone1, 
							s.code,
							SUM(
								CASE 
									WHEN e.purchase_order_id IS NOT NULL AND e.purchase_order_id != 0 THEN e.expense_amount
									WHEN (e.purchase_order_id IS NULL OR e.purchase_order_id = 0) AND xp.manual_po_number IS NOT NULL THEN e.expense_amount
									ELSE 0
								END
							) AS expense_gtotal
						FROM xin_suppliers s

						LEFT JOIN purchase_order po ON po.supplier_id = s.supplier_id

						LEFT JOIN xin_payable xp ON (
							xp.manual_po_number IS NOT NULL AND xp.subcon_id = s.supplier_id
						)

						LEFT JOIN expenses e ON (
							(po.purchase_order_id = e.purchase_order_id AND e.purchase_order_id IS NOT NULL AND e.purchase_order_id != 0)
							OR
							((e.purchase_order_id IS NULL OR e.purchase_order_id = 0) AND e.purchase_order_no = xp.manual_po_number)
						)

						GROUP BY s.supplier_id
						ORDER BY s.supplier_name ASC, s.code ASC
					";


		$suppliers = $this->db->query($sql)->result();

		foreach ($suppliers as $s) {
			$combined[] = (object)[
				'type' => 'supplier',
				'code' => $s->code,
				'name' => $s->supplier_name,
				'phone' => $s->phone1,
				'expense_gtotal' => $s->expense_gtotal,
			];
		}

		// Fetch clients
		$this->db->select("
						cust_code1,
						client_phone,
						CASE 
							WHEN cust_type = 0 THEN client_company_name
							WHEN cust_type = 1 THEN f_name
							ELSE NULL
						END AS client_name
					", false);
		$this->db->from("clients");
		$this->db->order_by('client_name', 'ASC');
		$this->db->order_by('cust_code1', 'ASC');
		$clients = $this->db->get()->result();

		foreach ($clients as $c) {
			$combined[] = (object)[
				'type' => 'client',
				'code' => $c->cust_code1,
				'name' => $c->client_name,
				'phone' => $c->client_phone,
			];
		}

		return $combined;
	}


	function get_po_detail($id)
	{
		$this->db->select('*');
		$this->db->from('purchase_order');
		$this->db->where('purchase_order_id', $id);
		$query = $this->db->get();
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return null;
		}
	}
}
