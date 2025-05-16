<?php
class Payable extends My_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Xin_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Project_model");
		$this->load->model("Payable_model");
		$this->load->model("Purchase_model");
	}
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
	public function index()
	{
		$session = $this->session->userdata('username');
		$data['title'] = $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['all_customers'] = $this->Xin_model->all_customer();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_packings'] = $this->Xin_model->get_packing_type();
		$data['get_payment_methods'] = $this->Xin_model->payment_methods();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['get_all_customer'] = $this->Payable_model->get_clients();


		$data['breadcrumbs'] = 'Payable';
		$data['path_url'] = 'payable';
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('3301', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/finance/get_payable", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function payable_list()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		// $data['all_projects'] = $this->Xin_model->get_all_project();
		// $data['all_customers'] = $this->Xin_model->all_customer();
		// $data['all_suppliers'] = $this->Xin_model->all_suppliers();
		// $data['all_countries'] = $this->Xin_model->get_countries();
		// $data['all_packings'] = $this->Xin_model->get_packing_type();
		$data['get_payment_methods'] = $this->Xin_model->payment_methods();
		// $data['get_gst'] = $this->Xin_model->get_gst();
		if (isset($_GET['start_date']) && date('Y-m-d', strtotime($_GET['start_date'])) != "1970-01-01") {
			$start_date = date('Y-m-d h:i:s', strtotime($_GET['start_date']));
		} else {
			$start_date = "";
		}
		if (isset($_GET['end_date']) && date('Y-m-d', strtotime($_GET['end_date'])) != date('Y-m-d', strtotime("1970-01-01"))) {
			$end_date = date('Y-m-d h:i:s', strtotime($_GET['end_date']));
		} else {
			$end_date = "";
		}
		if (isset($_GET['search_status'])) {
			$search_status = $_GET['search_status'];
		} else {
			$search_status = "";
		}
		if (isset($_GET['search_supplier'])) {
			$search_supplier = $_GET['search_supplier'];
		} else {
			$search_supplier = "";
		}

		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (!empty($session)) {
			$this->load->view("admin/finance/get_payable", $data);
		} else {
			redirect('admin/');
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$payable = $this->Payable_model->payable_list($search_supplier, $start_date, $end_date, $search_status);

		// $payable=$this->db->select('xin_payable.*,SUM(expense.expense_amount) as potal')->from('expense')->group_by('expense.purchase_order_id');
		// $payable =$this->db->where('purchase_order')
		// print_r($payable->result());exit;
		$data = array();

		foreach ($payable->result() as $r) {

			$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-payable_id="' . $r->payable_id  . '"><span class="fa fa-pencil"></span></button></span>';
			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light view-data" data-toggle="modal" data-target=".view-modal-data" data-payable_id="' . $r->payable_id . '"><span class="fa fa-eye"></span></button></span>';
			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->invoice_no  . '"><span class="fa fa-trash"></span></button></span>';
			//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/purchase/pdf_create/'.$r->purchase_order_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			//$combhr = $download.$edit.$delete;
			if ($r->status == "Amount Paid") {
				$combhr = $view . $delete;
			} else if ($r->status == "Partially Paid") {
				$combhr = $view . $edit . $delete;
			} else {
				$combhr = $view . $edit . $delete;
			}

			$data[] = array(
				$combhr,
				$r->invoice_no,
				$r->purchase_order_no,
				$r->supplier_name,
				$r->potal,
				// $r->due_date,
				date('d-m-Y', strtotime($r->created_datetime)),
				$r->status


			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $payable->num_rows(),
			"recordsFiltered" => $payable->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}


	public function add_payable()
	{
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();


		if ($this->input->post('type') == "add_payable") {

			if ($this->input->post('purchase_order_id') == '') {
				$Return['error'] = "Select Purchase Order";
			} else if ($this->input->post('invoice_no') == '') {
				$Return['error'] = "Enter Invoice Number";
			} else if ($this->input->post('amount') == '') {
				$Return['error'] = "Enter Amount";
			} else if ($this->input->post('do_no') == '') {
				$Return['error'] = "DO Number Required";
			}
			// else if($this->input->post('is_payable_gst') != "1"){
			// 	if($this->input->post('is_payable_gst') == ""){
			// 		$Return['error'] = "Select GST Field";
			// 	}
			// }

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			if (is_uploaded_file($_FILES['payment_picture']['tmp_name'])) {
				//checking image type
				$allowed =  array('png', 'jpg', 'jpeg', 'pdf', 'gif');
				$filename = $_FILES['payment_picture']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

				if (in_array($ext, $allowed)) {
					$tmp_name = $_FILES["payment_picture"]["tmp_name"];
					$profile = "uploads/payment/";
					$set_img = base_url() . "uploads/payment/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$name = basename($_FILES["payment_picture"]["name"]);
					$newfilename = 'payment_' . round(microtime(true)) . '.' . $ext;
					move_uploaded_file($tmp_name, $profile . $newfilename);
					$fname = $newfilename;
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			} else {
				$fname = '';
			}
			if ($this->input->post('gstNumber') == 0 || $this->input->post('gstNumber') == '') {
				$newgst = 9;
			} else {
				$newgst = $this->input->post('gstNumber');
			}

			if ($this->input->post('remaining_amount') == 0) {
				$status = "Amount Paid";
			} else {
				$status = "Partially Paid";
			}

			$data = array(
				'purchase_order_id' 		=> $this->input->post('purchase_order_id'),
				'invoice_no' 				=> $this->input->post('invoice_no'),
				'do_no' 				    => $this->input->post('do_no'),
				'purchase_order_total'      => $this->input->post('ord_total'),
				'gst_num_po_total'          => $newgst,
				'gst_on_po_total'           => $this->input->post('gst_val'),
				'after_gst_po_gt'           => $this->input->post('def_val'),
				'amount' 					=> $this->input->post('amount'),
				'gst' 						=> $this->input->post('payable_gst'),
				'is_gst_inclusive'			=> $this->input->post('is_payable_gst'),
				'gst_value'					=> $this->input->post('amount') * ($this->input->post('payable_gst') / 100),
				'total' 					=> $this->input->post('payable_total_amount'),
				'due_date' 					=> $this->input->post('due_date'),
				'payment_type' 				=> $this->input->post('payment_type'),
				'date'						=> $this->input->post('date'),
				'pay_details'               => $this->input->post('pay_details'),
				'pay_status'                => '',
				'remark'                    => $this->input->post('remark'),
				'attachment'				=> $fname,
				'exp_attachment'			=> $fname,
				'remaining_amount'          => $this->input->post('remaining_amount'),
				'status'                    => $status,
				'created_by'				=> $_SESSION['username']['user_id'],
				'created_datetime'			=> date('Y-m-d h:i:s'),
				'modified_by'				=> $_SESSION['username']['user_id'],
				'modified_datetime'			=> date('Y-m-d h:i:s')
			);

			$result = $this->Payable_model->add($data);

			if ($result) {

				$Return['result'] = $this->lang->line('xin_payable_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		} else {

			if ($this->input->post('type') == "edit_payable") {
			}
		}
	}

	function getPODetails($id)
	{
		$res['po_detail'] = $this->db->select('purchase_order.*,projects.project_title,users.first_name,users.last_name,xin_suppliers.supplier_name,SUM(purchase_order_item_mapping.prd_total) as ord_total')
			->from('purchase_order')
			->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id', 'left')
			->join('projects', 'purchase_order.project_id=projects.project_id', 'left')
			->join('xin_suppliers', 'purchase_order_item_mapping.supplier_id=xin_suppliers.supplier_id', 'left')
			->join('users', 'purchase_order.po_for=users.id', 'left')
			->where('purchase_order.purchase_order_id', $id)
			->get()->result();
		// $res['grn_check']=$this->db->where('pur_order_id',$id)->get('grn_item_mapping')->result();
		// $res['grn_check1']=$this->db->where('po_number',$id)->get('grn_tbl')->result();


		$res['po_items'] = $this->db->select('purchase_order_item_mapping.*,product.product_name')
			->from('purchase_order_item_mapping')
			->join('product', 'purchase_order_item_mapping.prd_id=product.product_id', 'left')
			->where('purchase_order_item_mapping.porder_id', $id)
			->get()->result();

		echo json_encode($res);
	}

	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('payable_id');
		$result = $this->Payable_model->read_payable($id);

		if (!empty($result)) {
			$payable = $result[0];
			$data = array(
				'payable_id' => $payable->payable_id,
				'purchase_order_id' => $payable->purchase_order_id,
				'purchase_order_no' => ($payable->purchase_order_no) ?? $payable->manual_po_number,
				'invoice_no' => $payable->invoice_no,
				'do_no' => $payable->do_no,
				'amount' => $payable->amount,
				// 'is_gst_inclusive' => $payable->is_gst_inclusive,
				// 'gst' => $payable->gst,
				// 'gst_value' => $payable->gst_value,
				'total' => $payable->total,
				'due_date' => ($payable->due_date),
				'manual_due_date' => $payable->manual_due_date,
				// 'project_id_subcon' => $payable->project_id_subcon,
				'payment_type' => $payable->payment_type,
				'total_amount' => $payable->total_amount,
				'status' => $payable->status,
				'get_payment_methods' => $this->Xin_model->get_payment_method()->result(),
				'total_paid_amount' => $this->Payable_model->payables_list($payable->invoice_no),
				'get_payables_list' => $this->Payable_model->get_payables_list($payable->payable_id),
				// 'get_invoice_list' => $this->db->where('payable_id',$payable->payable_id)->get('xin_payable')->result(),
				// 'gst_on_total' => $payable->gst_num_po_total,
				'gst_num' => $payable->gst_on_po_total,
				'po_gt' => $payable->after_gst_po_gt,
				'pay_detail' => $payable->pay_details,
				'remark' => $payable->remark,
				'attachment' => isset($payable->attachment) ? $payable->attachment : null, // Handling attachment
			);

			$session = $this->session->userdata('username');
			if (!empty($session)) {
				$this->load->view('admin/finance/dialog_payable', $data);
			} else {
				redirect('admin/');
			}
		} else {
			// Handle case when no result is found
			echo "No payable found for the given ID.";
		}
	}

	public function update()
	{

		if ($this->input->post('edit_type') == 'payable') {
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			$id = $this->input->post('payable_id');

			// if($this->input->post('remaining_amount1') == 0){
			// 	$status="Amount Paid";
			// }else{
			// 	$status="Partially Paid";			
			// }

			if (is_uploaded_file($_FILES['payment_picture_edit']['tmp_name'])) {
				//checking image type
				$allowed =  array('png', 'jpg', 'jpeg', 'pdf', 'gif');
				$filename = $_FILES['payment_picture_edit']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

				if (in_array($ext, $allowed)) {
					$tmp_name = $_FILES["payment_picture_edit"]["tmp_name"];
					$profile = "uploads/payment/";
					$set_img = base_url() . "uploads/payment/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$name = basename($_FILES["payment_picture_edit"]["name"]);
					$newfilename = 'receive_' . round(microtime(true)) . '.' . $ext;
					move_uploaded_file($tmp_name, $profile . $newfilename);
					$fname5 = $newfilename;
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			} else {
				$fname5 = '';
			}

			$data = array(
				'purchase_order_id' 		=> $this->input->post('purchase_order_id'),
				'invoice_no' 				=> $this->input->post('invoice_no1'),
				'do_no' 					=> $this->input->post('do_no1'),
				'after_gst_po_gt' 			=> $this->input->post('a_gst_po_gt'),
				'amount' 					=> $this->input->post('amount1'),
				'total'						=> $this->input->post('payable_total_amount1'),				
				'due_date' 					=> ($this->input->post('due_date1')) ?? '',
				'manual_due_date' 			=> ($this->input->post('manual_due_date1')) ?? '',
				'payment_type' 				=> $this->input->post('payment_type1'),
				'remaining_amount' 			=> $this->input->post('remaining_amount1'),
				'status' 					=> '',
				'remark'					=> $this->input->post('remark1'),
				'attachment'				=> $fname5,
				'exp_attachment'			=> $fname5,
				'pay_details' 				=> $this->input->post('pay_detail1'),
				'created_by'				=> $_SESSION['username']['user_id'],
				'created_datetime'			=> date('Y-m-d h:i:s'),
				'modified_by'				=> $_SESSION['username']['user_id'],
				'modified_datetime'			=> date('Y-m-d h:i:s'),
				'flag' 						=> 2,
			);

			$result = $this->Payable_model->add($data);

			if ($this->input->post('due_date1')) {
				$this->db->update('xin_payable', ['due_date' => $this->input->post('due_date1')], ['purchase_order_id' => $this->input->post('purchase_order_id')]);
				$due_date = $this->input->post('due_date1'); // Fetch the due date from the form
				$current_date = date('d-m-Y'); // Get the current date in 'YYYY-MM-DD' format
				if ($this->input->post('remaining_amount1') == 0) {
					$this->db->update('xin_payable', ['status' => "Amount Paid"], ['invoice_no' => $this->input->post('invoice_no1')]);
				} else {
					$this->db->update('xin_payable', ['status' => "Partially Paid"], ['invoice_no' => $this->input->post('invoice_no1')]);
				}
				if (strtotime($due_date) < strtotime($current_date)) { // Check if the due date is in the past
					$this->db->update('xin_payable', ['status' => "Overdue"], ['invoice_no' => $this->input->post('invoice_no1')]);
				}

			} else if ($this->input->post('manual_due_date1')) {
				$this->db->update('xin_payable', ['manual_due_date' => $this->input->post('manual_due_date1')], ['manual_po_number' => $this->input->post('purchase_order_no1')]);
				$due_date = $this->input->post('manual_due_date1'); // Fetch the due date from the form
				$current_date = date('d-m-Y'); // Get the current date in 'YYYY-MM-DD' format
				if ($this->input->post('remaining_amount1') == 0) {
					$this->db->update('xin_payable', ['status' => "Amount Paid"], ['invoice_no' => $this->input->post('invoice_no1')]);
				} else {
					$this->db->update('xin_payable', ['status' => "Partially Paid"], ['invoice_no' => $this->input->post('invoice_no1')]);
				}
				if (strtotime($due_date) < strtotime($current_date)) { // Check if the due date is in the past
					$this->db->update('xin_payable', ['status' => "Overdue"], ['invoice_no' => $this->input->post('invoice_no1')]);
				}
			}

			if ($result) {
				$Return['result'] = $this->lang->line('xin_payable_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	public function delete_payable()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Payable_model->delete($id);

		$this->db->delete('expenses', ['purchase_invoice_no' => $id]);

		if (isset($id)) {
			$Return['result'] = $this->lang->line('xin_payable_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
}
