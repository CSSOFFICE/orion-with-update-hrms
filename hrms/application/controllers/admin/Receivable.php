<?php
class Receivable extends My_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Xin_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Project_model");
		$this->load->model("Payable_model");
		$this->load->model("Receivable_model");
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
		$data['title'] = 'Receivable | ' . $this->Xin_model->site_title();
		// $data['get_all_projects'] = $this->Xin_model->get_all_project();
		// $data['all_units'] = $this->Xin_model->get_unit();
		$data['get_gst'] = $this->Xin_model->get_gst();
		// $data['get_customers'] = $this->Receivable_model->get_customers();
		$data['get_payment_methods'] = $this->Xin_model->get_payment_method()->result();



		$data['breadcrumbs'] = 'Receivable';
		$data['path_url'] = 'receivable';
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('3301', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/finance/receivable_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function receivable_list()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = 'Receivable | ' . $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['get_customers'] = $this->Receivable_model->get_customers();


		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$invoice = $this->Receivable_model->receivable_list();
		// echo $this->db->last_query();exit;
		// print_r($invoice->result());exit;
		$data = array();

		foreach ($invoice->result() as $r) {


			$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-invoice_id="' . $r->invoice_id   . '"><span class="fa fa-pencil"></span></button></span>';
			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->invoice_id  . '"><span class="fa fa-trash"></span></button></span>';
			//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/finance/pdf_create/'.$r->quotation_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			//$combhr = $download.$edit.$delete;
			//$combhr = $edit.$delete;

			$combhr = $edit . $delete;


			$data[] = array(
				$combhr,
				($r->f_name) ?? $r->client_company_name,
				$r->after_gst_inv_gt,
				$r->invoice_no,
				($r->status) ?? "Receive Not Started",
				($r->bill_date) ? date('d-m-Y', strtotime($r->bill_date)) : ''

			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $invoice->num_rows(),
			"recordsFiltered" => $invoice->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	public function read()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('invoice_id');
		$result = $this->Receivable_model->read_invoice($id);
		//  print_r($result);exit;
		$data = array(
			'invoice_pk' =>	$result[0]->inv_pk,
			'invoice_no' => $result[0]->invoice_no,
			'invoice_date' => $result[0]->invoice_due_date,
			'quotation_no' => $result[0]->quotation_no,
			// 'amount' => $result[0]->amount,
			'due_date' => $result[0]->invoice_due_date,
			// 'payment_type' => $result[0]->payment_type,
			'total_amount' => $result[0]->after_gst_inv_gt,
			'gst_on_total' => $result[0]->gst_num_inv_total,
			'after_gst' => $result[0]->after_gst_inv_gt,
			'total_paid_amount' => $result[0]->paid_amount,
			// 'attachment' => $result[0]->attachment,
			'project_title' => $result[0]->project_title,
			'get_payment_methods' => $this->Xin_model->get_payment_method()->result(),
			'get_receivables' => $this->Receivable_model->get_receivable($result[0]->inv_pk),
			'get_status' => $this->Receivable_model->get_receivable_status($id)
		);
		// print_r($data);exit;
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/receivable/dialog_receivable', $data);
		} else {
			redirect('admin/');
		}
	}
	public function add()
	{


		if ($this->input->post('form') == "edit_receivable") {
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();


			if ($this->input->post('pay_amount') == '') {
				$Return['error'] = "Receiving Amount Required";
			}
			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			if ($this->input->post('hdn_remaining_amount1') > 0) {
				$status1 = "Partialy Received";
			} else if ($this->input->post('hdn_remaining_amount1') == 0) {
				$status1 = "Receive Complete";
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
					$newfilename = 'receivie_' . round(microtime(true)) . '.' . $ext;
					move_uploaded_file($tmp_name, $profile . $newfilename);
					$fname = $newfilename;
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			} else {
				$fname = '';
			}

			$data1 = array(
				'invoice_id' 	  => $this->input->post('invoice_id'),
				'after_gst_inv_gt' => $this->input->post('total_amount'),
				'remaining_amount' => $this->input->post('hdn_remaining_amount1'),
				'amount'          => $this->input->post('pay_amount'),
				'total'           => $this->input->post('pay_amount'),
				'attachment' => ($fname) ? $fname : $this->input->post('payment_picture'),
				'payment_type'    => $this->input->post('payment_type'),
				'created_by'	  => $_SESSION['username']['user_id'],
				'created_datetime' => date('Y-m-d h:i:s')

			);



			$result = $this->Receivable_model->add($data1);

			if ($result) {
				$this->db->update('finance_invoice', ['status' => $status1], ['invoice_id' => $this->input->post('invoice_id')]);
				$Return['result'] = "Receivable Updated";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		} else {
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();


			if ($this->input->post('remaining_amount') > 0) {
				$status = "Partialy Received";
			} else if ($this->input->post('remaining_amount') == 0) {
				$status = "Receive Complete";
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
					$newfilename = 'receive_' . round(microtime(true)) . '.' . $ext;
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
			$data = array(
				'invoice_id' 				=> $this->input->post('invoice_id'),
				'inv_total'					=> $this->input->post('ord_total'),
				'gst_num_inv_total' =>	$newgst,
				'attachment' => $fname,
				'gst_on_inv_total' =>	$this->input->post('gst_val'),
				'after_gst_inv_gt' => $this->input->post('def_val'),
				'amount' 					=> $this->input->post('amount'),
				'gst' 						=> $this->input->post('payable_gst'),
				'is_gst_inclusive'			=>  $this->input->post('is_payable_gst'),
				'gst_value'					=> $this->input->post('amount') * ($this->input->post('payable_gst') / 100),
				'total' 					=> $this->input->post('payable_total_amount'),
				'due_date' 					=> $this->input->post('due_date'),
				'payment_type' 				=> $this->input->post('payment_type'),
				'date'								=> $this->input->post('date'),
				// 'pay_details'=>$this->input->post('pay_details'),
				'status' => '',
				// 'remark'=>$this->input->post('remark'),
				'remaining_amount' => $this->input->post('remaining_amount'),
				'created_by'				=> $_SESSION['username']['user_id'],
				'created_datetime'			=> date('Y-m-d h:i:s')


			);
			// print_r($data);print_r($status);exit;
			$result = $this->Receivable_model->add($data);

			if ($result) {
				$this->db->update('finance_invoice', ['status' => $status], ['invoice_id' => $this->input->post('invoice_id')]);
				$Return['result'] = "Receivable Added";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	function delete_receive()
	{
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();

		$id = $this->uri->segment(4);

		// Fetch the attachment
		$attachment1 = $this->db->select('attachment')
			->from('xin_receivable')
			->where('invoice_id', $id)
			->get()
			->row();

		if ($attachment1) {
			$file_path = base_url() . 'uploads/payment/' . $attachment1->attachment;

			// Check if the file exists before unlinking
			if (file_exists($file_path)) {
				unlink($file_path);
			}
		}

		// Delete the record from the database
		$job = $this->db->delete('xin_receivable', ['invoice_id' => $id]);

		if ($job) {
			$Return['result'] = "Receivable Deleted";
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}

		// Output the response
		$this->output($Return);
		exit;
	}
}
