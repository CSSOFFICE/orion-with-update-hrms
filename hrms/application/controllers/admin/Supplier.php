<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		//load the model
		$this->load->model("Awards_model");
		$this->load->model("Xin_model");
		$this->load->library('email');
		$this->load->model("Department_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Finance_model");
		$this->load->model("Supplier_model");
		$this->load->model("Company_model");
		$this->load->helper('string');
	}

	/*Function to set JSON output*/
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
		// echo "1";exit;
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$system = $this->Xin_model->read_setting_info(1);
		if ($system[0]->module_awards != 'true') {
			redirect('admin/dashboard');
		}
		$data['title'] = $this->lang->line('xin_suppliers') . ' | ' . $this->Xin_model->site_title();
		$data['all_employees'] = $this->Xin_model->all_employees();
		$data['all_award_types'] = $this->Awards_model->all_award_types();
		$data['get_all_companies'] = $this->Xin_model->get_companies();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['breadcrumbs'] = $this->lang->line('xin_suppliers');
		$data['all_products'] = $this->db->select('product_name,product_id')->from('product')->get()->result();

		$data['path_url'] = 'supplier';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (in_array('2801', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/suppliers/supplier_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}


	public function get_emplyee_ajax()
	{
		$output = $this->db->get('xin_employees')->result();
		echo json_encode($output);
		exit();
	}

	public function subcontractors()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$data['title'] = "Sub Contractor" . ' | ' . $this->Xin_model->site_title();

		$data['breadcrumbs'] = "Sub Contractor";
		$data['all_subcontractors'] = $this->db->select('*')->from('xin_suppliers')->where('subcon_supplier', 'Yes')->get()->result();
		$data['all_projects'] = $this->db->select('*')->from('projects')->get()->result();

		$data['path_url'] = 'subcontractor';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (in_array('2806', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/subcontractor/subcontractor_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function subcontractor_list()
	{

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/subcontractor/subcontractor_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Xin_model->user_role_resource();

		$user_info = $this->Xin_model->read_user_info($session['user_id']);

		$data = array();
		$this->db->select('subcontractor.*,
						   projects.project_title as project_name,
						   suppliers.supplier_name as supplier_name,
						   
						   tasks.task_title as task_name')
			->from('subcontractor')
			->join('projects', 'projects.project_id = subcontractor.subcon_project_id', 'left')
			->join('xin_suppliers as suppliers', 'suppliers.supplier_id = subcontractor.subcon_sup_id', 'left')

			->join('tasks', 'tasks.task_id = subcontractor.subcon_task', 'left');
		$subcon_data = $this->db->get();

		foreach ($subcon_data->result() as $r) {



			$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-subcon_id="' . $r->subcon_id . '"><span class="fa fa-pencil"></span></button></span>';

			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->subcon_id . '"><span class="fa fa-trash"></span></button></span>';
			if (in_array('2915', $role_resources_ids)) {
				$expense = '<span data-toggle="tooltip" data-placement="top" title="Purchase Expense"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-subcon_id="' . $r->subcon_id . '" data-subcon_sup_id="' . $r->subcon_sup_id . '"><span class="fa fa-dollar"></span></button></span>';
			}else{
				$expense = '';
			}

			$combhr = $edit . $delete.$expense;

			$data[] = array(
				$combhr,

				$r->supplier_name,
				$r->project_name,
				($r->subcon_milestone == 1 ? 'PRELIMINARIES' : ($r->subcon_milestone == 2 ? 'INSURANCES' : ($r->subcon_milestone == 3 ? 'SCHEDULE OF WORKS' : ($r->subcon_milestone == 4 ? 'PLUMBING & SANITARY' : ($r->subcon_milestone == 5 ? 'ELEC & ACMV' : ($r->subcon_milestone == 6 ? 'EXTERNAL WORKS' : ($r->subcon_milestone == 7 ? 'PC & PS SUMS' : ($r->subcon_milestone == 8 ? 'Others' : '')))))))),
				$r->task_name,
				'<a href="' . base_url('uploads/subcontractor_attachment/' . $r->subcon_attachment) . '" target="_blank">Click to download</a>',
				$r->contracted_amount,
				$r->agreement_number

			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $subcon_data->num_rows(),
			"recordsFiltered" => $subcon_data->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function add_subcontractor()
	{

		if ($this->input->post('add_type') == 'subcontractor') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('project') == '') {
				$Return['error'] = "Please Select Project";
			} else if ($this->input->post('subcon') == '') {
				$Return['error'] = "Please Select Subcontractor";
			} else if ($this->input->post('milestone') == '') {
				$Return['error'] = "Please Select Milestone";
			} else if ($this->input->post('task') == '') {
				$Return['error'] = "Please Select Task";
			} else if ($this->input->post('contracted_ammount') == '') {
				$Return['error'] = "Please Enter Amount";
			}
			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			$insert_data = array(
				'subcon_sup_id' => $this->input->post('subcon'),
				'subcon_project_id' => $this->input->post('project'),
				'subcon_milestone' => $this->input->post('milestone'),
				'subcon_task' => $this->input->post('task'),
				'contracted_amount' => $this->input->post('contracted_ammount'),
				'agreement_number' => $this->input->post('agreement_number'),
				'created_at' => date('Y-m-d H:i:s') // Fixed date format to match MySQL's DATETIME format
			);

			// Handle Attachment Upload
			if (!empty($_FILES['attachment']['name'])) {
				$config['upload_path'] = './uploads/subcontractor_attachment/';
				$config['allowed_types'] = 'jpg|jpeg|png|pdf|txt|docx|doc|xlsx';
				$config['file_name'] = date('YmdHis') . '_' . $_FILES['attachment']['name']; // Rename file with datetime
				$this->load->library('upload');
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('attachment')) {
					$Return['error'] = $this->upload->display_errors();
					$this->output($Return);
					exit;
				} else {
					$post_image = $this->upload->data();
					$insert_data['subcon_attachment'] = $post_image['file_name'];
				}
			}

			$result = $this->db->insert('subcontractor', $insert_data);
			if ($result == TRUE) {
				$Return['result'] = "Sub Contractor Added";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function read_subcon()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();

		$id = $this->input->get('subcon_id');
		$data['subcontractors'] = $this->db->select('*')->from('xin_suppliers')->where('subcon_supplier', 'Yes')->get()->result();
		$data['all_projects'] = $this->db->select('projects.project_title,projects.project_id,projects.project_sn')->from('projects')->get()->result();
		$data['result'] = $this->db->select('*')->from('subcontractor')->where('subcon_id', $id)->get()->result();

		// echo "<pre>";print_r($data);exit;
		if (!empty($session)) {
			$this->load->view('admin/subcontractor/dialog_subcontractor', $data);
		} else {
			redirect('admin/');
		}
	}

	public function update_subcon()
	{
		if ($this->input->post('edit_type') == 'edit_subcontractor') {

			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();


			/* Define return | here result is used to return user data and error for error message */
			if ($this->input->post('project1') == '') {
				$Return['error'] = "Please Select Project";
			} else if ($this->input->post('subcon1') == '') {
				$Return['error'] = "Please Select Subcontractor";
			} else if ($this->input->post('milestone1') == '') {
				$Return['error'] = "Please Select Milestone";
			} else if ($this->input->post('task1') == '') {
				$Return['error'] = "Please Select Task";
			} else if ($this->input->post('contracted_ammount1') == '') {
				$Return['error'] = "Please Enter Amount";
			}
			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			$update_data = array(
				'subcon_sup_id' => $this->input->post('subcon1'),
				'subcon_project_id' => $this->input->post('project1'),
				'subcon_milestone' => $this->input->post('milestone1'),
				'subcon_task' => $this->input->post('task1'),
				'contracted_amount' => $this->input->post('contracted_ammount1'),
				'agreement_number' => $this->input->post('agreement_number1'),
				'modified_at' => date('Y-m-d H:i:s') // Fixed date format to match MySQL's DATETIME format
			);

			// Handle Attachment Upload
			if (!empty($_FILES['attachment1']['name'])) {
				$config['upload_path'] = './uploads/subcontractor_attachment/';
				$config['allowed_types'] = 'JPG|jpg|JPEG|jpeg|png|pdf|txt|docx|doc|xlsx';
				$config['file_name'] = date('YmdHis'); // Rename file with datetime
				$this->load->library('upload');
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('attachment1')) {
					$Return['error'] = $this->upload->display_errors();
					$this->output($Return);
					exit;
				} else {
					$post_image = $this->upload->data();
					$update_data['subcon_attachment'] = $post_image['file_name'];
					if ($this->input->post('old_attachment1')) {
						unlink(FCPATH . 'uploads/subcontractor_attachment/' . $this->input->post('old_attachment1'));
					}
				}
			} else {
				$update_data['subcon_attachment'] = $this->input->post('old_attachment1');
			}
			// print_r($update_data);exit;
			$subcon_id = $this->uri->segment(4);
			$result = $this->db->update('subcontractor', $update_data, array('subcon_id' => $subcon_id));
			if ($result == TRUE) {
				$Return['result'] = "Sub Contractor Updated";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}

	public function subcon_expense_view()
	{	
		// print_r($this->input->get('subcon_id'));exit;

		$data['all_purchase_orders'] = $this->db->select('*')->from('purchase_order')->get()->result();		
		$data['subcon_detail'] = $this->db->select("
        subcontractor.contracted_amount,
        subcontractor.agreement_number,
        projects.project_title,
        projects.project_id,
        CASE 
            WHEN subcontractor.subcon_milestone = 1 THEN 'Preliminaries'
            WHEN subcontractor.subcon_milestone = 2 THEN 'Insurance'
            WHEN subcontractor.subcon_milestone = 3 THEN 'Schedule Of Works'
            WHEN subcontractor.subcon_milestone = 4 THEN 'Plumbing & Sanitary'
            WHEN subcontractor.subcon_milestone = 5 THEN 'Elec & Acmv'
			WHEN subcontractor.subcon_milestone = 6 THEN 'External Works'
			WHEN subcontractor.subcon_milestone = 7 THEN 'PC & PS Sums'
			WHEN subcontractor.subcon_milestone = 8 THEN 'Others'
            ELSE 'Others'
        END AS milestone_title,
        tasks.task_title
    ")
    ->from('subcontractor')
    ->join('projects', 'projects.project_id = subcontractor.subcon_project_id', 'left')
    ->join('tasks', 'tasks.task_id = subcontractor.subcon_task', 'left')
    ->where('subcon_id', $this->input->get('subcon_id'))
    ->get()
    ->result();

		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/subcontractor/subcon_expense',$data);
		} else {
			redirect('admin/');
		}
	}

	public function getPODetails($poid){
		$result=$this->Supplier_model->get_po_detail($poid);
		echo json_encode($result);
	}


	public function supplier_list()
	{

		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/suppliers/supplier_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$role_resources_ids = $this->Xin_model->user_role_resource();

		$user_info = $this->Xin_model->read_user_info($session['user_id']);
		// if($user_info[0]->user_role_id==1){
		// 	$supplier = $this->Supplier_model->get_supplier();
		// } else {
		// 	if(in_array('2805',$role_resources_ids)) {
		// 		$supplier = $this->Supplier_model->get_company_supplier($user_info[0]->company_id);
		// 	} 
		// else {
		// 	$appraisal = $this->supplier_model->get_employee_performance_appraisal($session['user_id']);
		// }
		// }
		$data = array();
		$this->db->select('*');
		$this->db->from('xin_suppliers')->order_by('code', 'ASC');
		// $this->db->join('xin_employees', 'xin_suppliers.emps_id = xin_employees.user_id', 'left'); // Perform left join with employee table

		// Apply condition for supplier_type being 'sundry' and emps_id having a value
		// $this->db->or_where('xin_suppliers.supplier_type', 'sundry');
		// $this->db->or_where('xin_suppliers.emps_id IS NOT NULL');

		$supplier = $this->db->get();


		// $supplier = $query->result_array();
		// print_r($supplier->result());exit;
		foreach ($supplier->result() as $r) {


			if (in_array('2803', $role_resources_ids)) { //edit
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-supplier_id="' . $r->supplier_id . '"><span class="fa fa-pencil"></span></button></span>';
			} else {
				$edit = '';
			}
			if (in_array('2804', $role_resources_ids)) { // delete
				$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->supplier_id . '"><span class="fa fa-trash"></span></button></span>';
			} else {
				$delete = '';
			}
			if (in_array('2805', $role_resources_ids)) { //view
				$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light view-data" data-toggle="modal" data-target=".view-modal-data-bg" data-p_supplier_id="' . $r->supplier_id . '"><span class="fa fa-eye"></span></button></span>';
			} else {
				$view = '';
			}
			$combhr = $edit . $view . $delete;

			// if ($r->supplier_type == 1) {
			// 	$type = "Supplier";
			// } else if ($r->supplier_type == 'sundry') {
			// 	$type = "Sundry Creditor";
			// }
			//else if ($r->supplier_type == 3) {
			// 	$type = "Vendor";
			// }
			$data[] = array(
				$combhr,
				$r->code,
				$r->subcon_supplier,
				$r->supplier_name,
				$r->supplier_terms,
				$r->supplier_gst,

			);

			// Debugging
			// if (($r->supplier_name) == null && ($r->emps_id != null)) {
			// 	echo "Debug: Supplier Name is null but emps_id is not null. emps_id: " . $r->emps_id . "<br>";
			// 	echo "Debug: Employee Name: " . $r->first_name . " " . $r->last_name . "<br>";
			// }

		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $supplier->num_rows(),
			"recordsFiltered" => $supplier->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}


	public function add_supplier()
	{

		if ($this->input->post('add_type') == 'supplier') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('add_type1') == '') {
				$Return['error'] = "Please Choose Add Type";
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			if ($this->input->post('add_type1') == 'manual') {
				/* Server side PHP input validation */



				// print_r($this->input->post('billing_addr_pic'));exit;
				// $items = $this->input->post('item');

				// if (!is_array($items)) {
				// Handle the case where 'item' is not an array, e.g., it's null or some other non-array value
				// $Return['error'] = "Please Add One or More Items";
				// } else if ($this->input->post('add_type1') == '') {
				// 	$Return['error'] = "Please Select Supplier Type";
				// } else if ($this->input->post('s_type') == 'sundry' && $this->input->post('employee_name') == '') {
				// 	$Return['error'] = "Employee Name Required"; // Error message for empty employee name when s_type is sundry
				if ($this->input->post('supplier_name') == '') {
					$Return['error'] = "Supplier Name Required"; // Error message for empty supplier name when s_type is not sundry
				} else if (!is_array($this->input->post('billing_addr_pic'))) {
					$Return['error'] = "Please Add One or More Billing Address";
					// } else if (!is_array($this->input->post('shipping_addr_pic'))) {
					// 	$Return['error'] = "Please Add One or More Shipping Address";
				} else if (!is_array($this->input->post('billing_addr_zipcode'))) {
					$Return['error'] = "Billing Address Postal Required";
					// } else if (!is_array($this->input->post('shipping_addr_zipcode'))) {
					// 	$Return['error'] = "Shipping Address Postal Required";
				}


				if ($Return['error'] != '') {
					$this->output($Return);
					exit;
				}


				// if ($this->input->post('supplier_name') !== null) {
				// 	$emp = $this->input->post('supplier_name');
				// } else if ($this->input->post('employee_name') !== null) {
				// 	$emp = $this->input->post('employee_name');
				// }


				// Get the supplier name from the input
				$supplier_name = $this->input->post('supplier_name');

				// Remove any non-alphabetic characters (e.g., numbers, spaces, etc.)
				$filtered_supplier_name = preg_replace('/[^a-zA-Z]/', '', $supplier_name);

				// Extract the first letter from the filtered supplier name
				if (!empty($filtered_supplier_name)) {
					$first_letter = strtoupper(substr($filtered_supplier_name, 0, 1));
				} else {
					// Fallback in case there's no alphabetic character, default to 'S'
					$first_letter = '';
				}

				// Fetch the last supplier entry to get the last number used in the code
				$this->db->select('code');
				$this->db->from('xin_suppliers');
				$this->db->like('code', $first_letter, 'after'); // Find codes starting with the first letter
				$this->db->order_by('code', 'DESC');
				$this->db->limit(1);
				$last_supplier = $this->db->get()->row_array();

				if ($last_supplier) {
					// Extract the number part from the code, e.g., 'S1001' -> 1001
					$last_number = (int) filter_var($last_supplier['code'], FILTER_SANITIZE_NUMBER_INT);
					$new_number = $last_number + 1;
				} else {
					// If no previous supplier exists with the same first letter, start from 1001
					$new_number = 1000;
				}

				// Generate the new supplier code
				$code = $first_letter . $new_number;

				// Prepare the data to insert
				$data = array(
					'type' => $this->input->post('add_type1'),
					'code' => $code,
					'supplier_name' => $this->input->post('supplier_name'),
					'supplier_terms' => $this->input->post('sup_terms'),
					'supplier_gst' => $this->input->post('gst_sup'),
					'subcon_supplier' => ($this->input->post('subcontractor') == 'Yes') ? $this->input->post('subcontractor') : 'No',
					// 'address_1' => $this->input->post('address_1'),
					// 'address_2' => $this->input->post('address_2'),
					// 'address_3' => $this->input->post('address_3'),
					// 'address_4' => $this->input->post('address_4'),
					// 'contact_person' => $this->input->post('contact_person'),
					// 'phone1' => $this->input->post('tel_no_1'),
					// 'phone2' => $this->input->post('tel_no_2'),
					// 'fax1' => $this->input->post('fax1'),
					// 'email_id' => $this->input->post('email_id'),
					'created_at' => date('Y-m-d H:i:s') // Fixed date format to match MySQL's DATETIME format
				);

				// Insert supplier data into the database
				$result = $this->Supplier_model->add($data);
				// print_r($data);exit;


				if ($result) {
					// $this->db->update('xin_suppliers', ['supplier_number' => $format], ['supplier_id' => $result]);
					if (!empty($_POST['billing_addr_pic'])) {
						$billing_pics = $this->input->post('billing_addr_pic');
						$billing_contacts = $this->input->post('billing_addr_contant_number');
						$billing_addresses = $this->input->post('billing_address');
						$billing_zipcodes = $this->input->post('billing_addr_zipcode');
						$billing_emails = $this->input->post('billing_addr_email');

						for ($i = 0; $i < count($billing_pics); $i++) {
							// Extra check to make sure data is not empty before insert
							if (!empty($billing_pics[$i]) || !empty($billing_addresses[$i]) || !empty($billing_zipcodes[$i])|| !empty($billing_emails[$i])|| !empty($billing_contacts[$i])) {
								$data_opt = array(
									'supplier_id' => $result,
									'pic' => $billing_pics[$i],
									'contact' => $billing_contacts[$i],
									'address' => $billing_addresses[$i],
									'zipcode' => $billing_zipcodes[$i],
									'email' => $billing_emails[$i],
								);
								$this->db->insert('xin_supplier_billing', $data_opt);
							}
						}
					}

					if (!empty($_POST['shipping_addr_pic'])) {
						$shipping_pics = $this->input->post('shipping_addr_pic');
						$shipping_contacts = $this->input->post('shipping_addr_contant_number');
						$shipping_addresses = $this->input->post('shipping_address');
						$shipping_zipcodes = $this->input->post('shipping_addr_zipcode');
						$shipping_emails = $this->input->post('shipping_addr_email');

						for ($i = 0; $i < count($shipping_pics); $i++) {
							if (!empty($shipping_pics[$i]) || !empty($shipping_addresses[$i])) {
								$data_opt = array(
									'supplier_id' => $result,
									'pic' => $shipping_pics[$i],
									'contact' => $shipping_contacts[$i],
									'address' => $shipping_addresses[$i],
									'zipcode' => $shipping_zipcodes[$i],
									'email' => $shipping_emails[$i],
								);
								$this->db->insert('xin_supplier_shipping', $data_opt);
							}
						}
					}

					if (isset($_POST['item'])) {
						if (count($this->input->post('item')) > 0) {


							for ($i = 0; $i < count($this->input->post('item')); $i++) {

								$data_opt = array(
									'supplier_id' => $result,
									'supplier_item_name' =>  $this->input->post('item')[$i],
									'supplier_item_description' => $this->input->post('description')[$i],
									'supplier_item_price' => $this->input->post('price')[$i],
								);
								$this->db->insert('xin_supplier_item_mapping', $data_opt);
							}
						}
					}
				}

				if ($result == TRUE) {
					$Return['result'] = $this->lang->line('xin_supplier_success_added');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			} else
			if ($this->input->post('add_type1') == 'bulk') {

				//validate whether uploaded file is a csv file
				$csvMimes = array('application/xslx', 'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
				// echo "1";exit;

				if ($_FILES['file']['name'] === '') {
					$Return['error'] = $this->lang->line('xin_employee_imp_allowed_size');
				} else {
					// echo "1";exit;
					// print_r($_FILES['file']);exit;
					if (in_array($_FILES['file']['type'], $csvMimes)) {
						// echo "1";exit;

						if (is_uploaded_file($_FILES['file']['tmp_name'])) {

							// check file size
							if (filesize($_FILES['file']['tmp_name']) > 2000000) {
								$Return['error'] = $this->lang->line('xin_error_employees_import_size');
							} else {

								//open uploaded csv file with read only mode
								$csvFile = fopen($_FILES['file']['tmp_name'], 'r');

								//skip first line
								fgetcsv($csvFile);




								//parse data from csv file line by line


								// Assuming you have established the database connection somewhere in your code.

								// Loop through each line of the CSV file
								while (($line = fgetcsv($csvFile)) !== FALSE) {
									// Get the supplier name from the input
									$supplier_name = $line[0];

									// Remove any non-alphabetic characters (e.g., numbers, spaces, etc.)
									$filtered_supplier_name = preg_replace('/[^a-zA-Z]/', '', $supplier_name);

									// Extract the first letter from the filtered supplier name
									if (!empty($filtered_supplier_name)) {
										$first_letter = strtoupper(substr($filtered_supplier_name, 0, 1));
									} else {
										// Fallback in case there's no alphabetic character, default to 'S'
										$first_letter = '';
									}

									// Fetch the last supplier entry to get the last number used in the code
									$this->db->select('code');
									$this->db->from('xin_suppliers');
									$this->db->like('code', $first_letter, 'after'); // Find codes starting with the first letter
									$this->db->order_by('code', 'DESC');
									$this->db->limit(1);
									$last_supplier = $this->db->get()->row_array();

									if ($last_supplier) {
										// Extract the number part from the code, e.g., 'S1001' -> 1001
										$last_number = (int) filter_var($last_supplier['code'], FILTER_SANITIZE_NUMBER_INT);
										$new_number = $last_number + 1;
									} else {
										// If no previous supplier exists with the same first letter, start from 1001
										$new_number = 1000;
									}

									// Generate the new supplier code
									$code = $first_letter . $new_number;

									// Define the data to be inserted into the database
									$data = array(
										'code' => $code,
										'supplier_name' => ($line[0]) ?? '',
										'address_1' => ($line[1]) ?? '',
										'address_2' => ($line[2]) ?? '',
										'address_3' => ($line[3]) ?? '',
										'address_4' => ($line[4]) ?? '',
										'contact_person' => ($line[5]) ?? '',
										'phone1' => ($line[6]) ?? '',
										'phone2' => ($line[7]) ?? '',
										'fax1' => ($line[8]) ?? '',
										'email_id' => ($line[9]) ?? '',
										'created_at'     => date('Y-m-d H:i:s') // Fixed date format to match MySQL's DATETIME format
									);

									// Insert the data into the database
									$result = $this->Supplier_model->add($data);
									// Format the result with leading zeros, omitting them when the number is 100 or greater
									// $format = ($result >= 100) ? "SUP-" . $result : "SUP-" . sprintf("%03d", $result);
									// Do something with $format if needed
									// $this->db->update('xin_suppliers', ['supplier_number' => $format], ['supplier_id' => $result]);
								}


								//close opened csv file
								fclose($csvFile);

								$Return['result'] = "Import Done Successfully";
							}
						} else {
							$Return['error'] = "Failed to Import File";
						}
						$this->output($Return);
						exit;
					} else {
						$Return['error'] = $this->lang->line('xin_error_invalid_file');
					}
				} // file empty

				if ($Return['error'] != '') {
					$this->output($Return);
				}
			}
		}
	}

	public function read()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();

		$id = $this->input->get('supplier_id');
		$result = $this->Supplier_model->read_supplier_information($id);

		$data = array(
			'all_products' => $this->db->select('product_name,product_id')->from('product')->get()->result(),
			'all_items' => $this->db->where('supplier_id', $id)->get('xin_supplier_item_mapping')->result(),
			'billing_address' => $this->db->where('supplier_id', $id)->get('xin_supplier_billing')->result(),
			'shipping_address' => $this->db->where('supplier_id', $id)->get('xin_supplier_shipping')->result(),
			'supplier_name' => $result[0]->supplier_name,
			'supplier_terms' => $result[0]->supplier_terms,
			'supplier_gst' => $result[0]->supplier_gst,
			'subcon_supplier' => $result[0]->subcon_supplier,

			'code' => $result[0]->code,
		);
		// print_r($data);exit;
		if (!empty($session)) {
			$this->load->view('admin/suppliers/dialog_suppliers', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update()
	{
		if ($this->input->post('edit_type') == 'supplier') {

			// $id = $this->uri->segment(4);
			$id = $this->input->post('supplier_id');
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			if ($this->input->post('supplier_name') == '') {
				$Return['error'] = "Supplier Name Required";
			} else if (!is_array($this->input->post('billing_addr_pic1'))) {
				$Return['error'] = "Please Add One or More Billing Address";
				// } else if (!is_array($this->input->post('shipping_addr_pic1'))) {
				// 	$Return['error'] = "Please Add One or More Shipping Address";
			} else if (!is_array($this->input->post('billing_addr_zipcode1'))) {
				$Return['error'] = "Billing Address Postal Required";
				// } else if (!is_array($this->input->post('shipping_addr_zipcode1'))) {
				// 	$Return['error'] = "Shipping Address Postal Required";
			}
			// else if ($this->input->post('person_name') == '') {
			// 	$Return['error'] = "Contact Person Required";
			// } else if ($this->input->post('contact_number') == '') {
			// 	$Return['error'] = "Contact Person Number Required";
			// } else if ($this->input->post('address') == '') {
			// 	$Return['error'] = "Contact Person Address Required";
			// } else if ($this->input->post('pocode') == '') {
			// 	$Return['error'] = "PO Code Required";
			// } else if ($this->input->post('s_type') == '') {
			// 	$Return['error'] = "Please Select Supplier Type";
			// }


			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			if ($this->input->post('supplier_name') == $this->input->post('old_name')) {
				$code =	$this->input->post('old_code');
			} else {
				// Get the supplier name from the input
				$supplier_name = $this->input->post('supplier_name');

				// Remove any non-alphabetic characters (e.g., numbers, spaces, etc.)
				$filtered_supplier_name = preg_replace('/[^a-zA-Z]/', '', $supplier_name);

				// Extract the first letter from the filtered supplier name
				if (!empty($filtered_supplier_name)) {
					$first_letter = strtoupper(substr($filtered_supplier_name, 0, 1));
				} else {
					// Fallback in case there's no alphabetic character, default to 'S'
					$first_letter = '';
				}

				// Fetch the last supplier entry to get the last number used in the code
				$this->db->select('code');
				$this->db->from('xin_suppliers');
				$this->db->like('code', $first_letter, 'after'); // Find codes starting with the first letter
				$this->db->order_by('code', 'DESC');
				$this->db->limit(1);
				$last_supplier = $this->db->get()->row_array();

				if ($last_supplier) {
					// Extract the number part from the code, e.g., 'S1001' -> 1001
					$last_number = (int) filter_var($last_supplier['code'], FILTER_SANITIZE_NUMBER_INT);
					$new_number = $last_number + 1;
				} else {
					// If no previous supplier exists with the same first letter, start from 1001
					$new_number = 1000;
				}

				// Generate the new supplier code
				$code = $first_letter . $new_number;
			}
			// print_r($this->input->post());exit;

			$data = array(

				'type' => $this->input->post('add_type1'),
				'code' => $code,
				'supplier_name' => $this->input->post('supplier_name'),
				'supplier_terms' => $this->input->post('sup_terms1'),
				'supplier_gst' => $this->input->post('gst_sup1'),
				'subcon_supplier' => $this->input->post('subcontractor1'),
				// 'address_1' => $this->input->post('address_1'),
				// 'address_2' => $this->input->post('address_2'),
				// 'address_3' => $this->input->post('address_3'),
				// 'address_4' => $this->input->post('address_4'),
				// 'contact_person' => $this->input->post('contact_person'),
				// 'phone1' => $this->input->post('tel_no_1'),
				// 'phone2' => $this->input->post('tel_no_2'),
				// 'fax1' => $this->input->post('fax1'),
				// 'email_id' => $this->input->post('email_id'),
				'updated_at' => date('Y-m-d H:i:s') // Fixed date format to match MySQL's DATETIME format
			);

			// $result = $this->Supplier_model->add($data);
			$result = $this->db->update('xin_suppliers', $data, ['supplier_id' => $id]);
			$this->db->delete('xin_supplier_item_mapping', ['supplier_id' => $id]);
			$this->db->delete('xin_supplier_billing', ['supplier_id' => $id]);
			$this->db->delete('xin_supplier_shipping', ['supplier_id' => $id]);
			// if($this->input->post('s_type')=="Supplier"){
			// 	$format="SUP-00".$result;				
			// }else if($this->input->post('s_type')=="Subcon"){
			// 	$format="SUB-00".$result;				
			// }else if(($this->input->post('s_type')=="Vendor")){
			// 	$format="VEN-00".$result;				
			// }
			if (isset($_POST['billing_addr_pic1'])) {
				if (count($this->input->post('billing_addr_pic1')) > 0) {
					for ($i = 0; $i < count($this->input->post('billing_addr_pic1')); $i++) {
						$pic = trim($this->input->post('billing_addr_pic1')[$i]);
						$address = trim($this->input->post('billing_address1')[$i]);
						$zipcode = trim($this->input->post('billing_addr_zipcode1')[$i]);
						$contact = trim($this->input->post('billing_addr_contant_number1')[$i]);

						// Only insert if all required fields are not empty or zero
						if (!empty($pic) || !empty($address) || !empty($zipcode) || !empty($contact)) {
							$data_opt = array(
								'supplier_id' => $id,
								'pic' => $pic,
								'contact' => $contact,
								'address' => $address,
								'zipcode' => $zipcode,
								'email' => $this->input->post('billing_addr_email1')[$i],
							);
							$this->db->insert('xin_supplier_billing', $data_opt);
						}
					}
				}
			}

			if (isset($_POST['shipping_addr_pic1'])) {
				if (count($this->input->post('shipping_addr_pic1')) > 0) {

					for ($i = 0; $i < count($this->input->post('shipping_addr_pic1')); $i++) {
						$pic = trim($this->input->post('shipping_addr_pic1')[$i]);
						$contact = trim($this->input->post('shipping_addr_contant_number1')[$i]);
						$address = trim($this->input->post('shipping_address1')[$i]);
						$zipcode = trim($this->input->post('shipping_addr_zipcode1')[$i]);
						$email = trim($this->input->post('shipping_addr_email1')[$i]);

						// Save only if at least one important field is not empty or zero
						if ($pic != '' || $contact != '' || $address != '' || $zipcode != '' || $email != '') {
							$data_opt = array(
								'supplier_id' => $id,
								'pic' => $pic,
								'contact' => $contact,
								'address' => $address,
								'zipcode' => $zipcode,
								'email' => $email,
							);
							$this->db->insert('xin_supplier_shipping', $data_opt);
						}
					}
				}
			}

			if (isset($_POST['u_item'])) {

				if (count($this->input->post('u_item')) > 0) {
					for ($i = 0; $i < count($this->input->post('u_item')); $i++) {
						$data_opt = array(
							'supplier_id' => $id,
							'supplier_item_name' =>  $this->input->post('u_item')[$i],
							'supplier_item_description' => $this->input->post('u_description')[$i],
							'supplier_item_price' => $this->input->post('u_price')[$i],
						);
						$this->db->insert('xin_supplier_item_mapping', $data_opt);
					}
				}
			}
			if ($result) {



				$Return['result'] = $this->lang->line('xin_supplier_success_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	public function delete()
	{
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */

		$id = $this->uri->segment(4);
		$result = $this->db->delete('xin_suppliers', ['supplier_id' => $id]);
		$this->db->delete('xin_supplier_item_mapping', ['supplier_id' => $id]);
		$this->db->delete('xin_supplier_billing', ['supplier_id' => $id]);
		$this->db->delete('xin_supplier_shipping', ['supplier_id' => $id]);
		// $this->Supplier_model->delete_record($id);
		// print_r($it);exit;
		if ($result) {
			// $this->db->delete('xin_supplier_item_mapping', ['supplier_id' => $id]);
			$Return['result'] = $this->lang->line('xin_supplier_success_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
	}
}
