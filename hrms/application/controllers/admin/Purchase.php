<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'third_party/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options; // Import the Options class
class Purchase extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Xin_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Project_model");
		$this->load->model("Purchase_model");
		$this->load->model("Product_model");



		$this->load->library('Pdf');
		$this->load->helper('string');
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
		$data['title'] = 'Purchase Order | ' . $this->Xin_model->site_title();
		$data['all_warehouse'] = $this->db->get('warehouse')->result();

		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['all_customers'] = $this->db->get('xin_employees')->result();
		$data['all_subcontractor'] = $this->db->where('subcon_supplier', 'Yes')->get('xin_suppliers')->result();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_packings'] = $this->Xin_model->get_packing_type();
		$data['get_payment_methods'] = $this->Xin_model->payment_methods();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['term_condition'] = $this->db->get('xin_term_condition')->result();
		$data['all_products'] = $this->Purchase_model->get_all_products();


		$data['breadcrumbs'] = 'Purchase Order';
		$data['path_url'] = 'purchase_order';
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('2901', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/purchase/purchase_order_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}


	public function purchase_requistion()
	{
		$session = $this->session->userdata('username');
		$data['title'] = 'Material Requisition Form (MRF) | ' . $this->Xin_model->site_title();
		$data['all_projects'] = $this->db->get('projects')->result();
		// $data['all_warehouse'] = $this->db->get('warehouse')->result();
		$data['all_subcontractor'] = $this->db->where('subcon_supplier', 'Yes')->get('xin_suppliers')->result();
		$data['all_customers'] =  $this->db->get('xin_employees')->result();
		// $data['all_suppliers'] = $this->Xin_model->all_suppliers();
		// $data['all_countries'] = $this->Xin_model->get_countries();
		// $data['all_packings'] = $this->Xin_model->get_packing_type();
		// $data['get_gst'] = $this->Xin_model->get_gst();
		// $data['all_payment_terms'] = $this->Xin_model->get_payment_term();
		$data['purchase_purpose'] = $this->db->get('purchase_purpose')->result();

		$data['all_products'] = $this->Purchase_model->get_all_products();


		$data['breadcrumbs'] = 'Material Requisition Form (MRF)';
		$data['path_url'] = 'purchase_requistion';
		$data['invoice_settings'] = $this->Xin_model->read_setting_info(1);

		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('2901', $role_resources_ids) || in_array('8001', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/purchase/purchase_requistion_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}


	///////////////////NEW///////////////////
	public function viewpurchaserequest()
	{
		$session = $this->session->userdata('username');
		$data['title'] = 'Material Requisition Form (MRF) Request | ' . $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'Pending Material Requisition Form (MRF) Request';
		$data['path_url'] = 'purchase_request';
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$data['all_list_pending'] = $this->Purchase_model->purchase_requistion_pending_list();

		if (in_array('2901', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/purchase/prequest", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data);
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}

	public function updatestatus($id)
	{
		$check = $this->db->update('purchase_requistion', ['status' => 'confirmed'], ['purchase_requistion_id' => $id]);

		if ($check) {
			$this->session->set_flashdata('success', "Purchase Request Updated");
			redirect('admin/purchase/viewpurchaserequest');
		}
	}
	////////////////////END////////////////////




	public function add_purchase_requistion()
	{

		print_r($_POST);exit;

		$session = $this->session->userdata('username');

		if ($this->input->post('add_type') == 'purchase_requistion') {


			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			// print_r($this->input->post());exit;

			// if($this->input->post('project_id')=='') {
			// 	$Return['error'] = $this->lang->line('xin_error_project_field');
			// } else

			if ($this->input->post('project_id') == '') {
				$Return['error'] = "Select Project";
			}
			// else if ($this->input->post('order_date') == '') {
			// 	$Return['error'] = "MRF Date is required";
			// }
			else if ($this->input->post('milestone_id') === '') {
				$Return['error'] = "Please Select Milestone";
			} else if ($this->input->post('task_id') === '') {
				$Return['error'] = "Please Select Task";
			} else if (count($this->input->post('product_id')) === 0) {
				for ($i = 0; $i < count($this->input->post('product_id')); $i++) {
					if ($this->input->post('product_id')[$i] != "") {
						$Return['error'] = "Add Items";
					}
				}
			} else if (count($this->input->post('product_id')) > 0) {
				for ($i = 0; $i < count($this->input->post('product_id')); $i++) {
					if ($this->input->post('product_id')[$i] == "") {
						$Return['error'] = "Add Items";
					}
				}
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			$role_resources_ids = $this->Xin_model->user_role_resource();

			//if (in_array('8001', $role_resources_ids)) {
			if (!empty($session)) {
				if ($this->input->post('status') != "") {
					$status = $this->input->post('status');
				} else {
					$status = "Pending Engineer Verification";
				}
				if ($this->input->post('status_reason') != "") {
					$status_reason = $this->input->post('status_reason');
				} else {
					$status_reason = "";
				}
				if ($this->input->post('chk_site') != "") {
					$location = implode(',', $this->input->post('chk_site'));
				} else {
					$location = "";
				}
				if ($this->input->post('check1') != "") {
					$site = implode(',', $this->input->post('check1'));
				} else {
					$site = "";
				}

				$data = array(
					'project_id' => $this->input->post('project_id'),
					// //'customer_id' => $this->input->post('customer_id'),
					// 'purchase' => $this->input->post('pp'),
					// 'location' => $this->input->post('location'),
					// 'site_address' => $this->input->post('s_address'),
					'location' => $location,
					'site' => $site,
					'crane' => ($this->input->post('crane') == 'others') ? 0 : $this->input->post('crane'),
					'others' => ($this->input->post('crane') == 'others') ? $this->input->post('other_crane') : '',
					'site_address' => ($this->input->post('site_address') == 'others') ? $this->input->post('other_site_address') : $this->input->post('site_address'),
					'mile_stone' => $this->input->post('milestone_id'),
					'task' => $this->input->post('task_id'),

					//'mrf_no'=> $this->input->post('mrf_no'),
					'order_date' => date('d-m-Y'),
					'supervisor' => $this->input->post('supervisor'),
					'sub_contractor' => $this->input->post('sub_contractor'),
					'earliest_date' => $this->input->post('earliest_date'),
					'latest_date' => $this->input->post('latest_date'),

					'created_by' => $_SESSION['username']['user_id'],
					'created_datetime' => date('Y-m-d h:i:s'),
					'status' => $status,

					'status_reason' => $status_reason
				);
				if ($this->input->post('status') == 'Engineer Confirmation') {
					$data['eng_status'] = "Yes";
					$data['eng_date'] = date('d-m-Y');
					$data['eng_id'] = $_SESSION['username']['user_id'];
				}

				$result = $this->Purchase_model->add($data);
				if ($this->input->post('project_id') != '') {
					// Fetch project code if available
					$pr_code_data = $this->db
						->select('project_code')
						->from('projects')
						->where('project_id', $this->input->post('project_id'))
						->get()
						->row();

					$pr_code = $pr_code_data ? $pr_code_data->project_code : '';

					// Month Sequence Start
					$currentMonth = date('m/y');

					// Handle sequence
					$this->db->trans_start(); // Start transaction

					$sequence = $this->Purchase_model->get_current_pr_sequence($currentMonth);
					$new_sequence = $sequence ? $sequence->sequence + 1 : 1;

					if ($sequence) {
						$this->Purchase_model->update_pr_sequence($currentMonth, $new_sequence);
					} else {
						$this->Purchase_model->insert_pr_sequence($currentMonth, $new_sequence);
					}

					$this->db->trans_complete(); // Complete transaction


					if ($pr_code) {
						// Generate new purchase order ID
						$new_porder_id = "OIS/PR/" . $pr_code . "/" . $currentMonth . "/" . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);

						// Update the purchase requisition
						$job_up = $this->db->update('purchase_requistion', ['porder_id' => $new_porder_id], ['purchase_requistion_id' => $result]);
					} else {
						// Generate new purchase order ID
						$new_porder_id = "OIS/PR/" . $currentMonth . "/" . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);

						// Update the purchase requisition
						$job_up = $this->db->update('purchase_requistion', ['porder_id' => $new_porder_id], ['purchase_requistion_id' => $result]);
					}
				}

				if ($result) {
					$this->events($result, $new_porder_id, $this->input->post('project_id'));
					if (count($this->input->post('product_id')) > 0) {

						$k = 1;
						for ($i = 0; $i < count($this->input->post('product_id')); $i++) {
							// $supplier = $this->input->post('supplier_id' . $k);

							$data_opt = array(
								'purchase_requistion_id' => $result,
								'product_id' => $this->input->post('product_id')[$i],
								'uom' => $this->input->post('uom')[$i],
								'level' => $this->input->post('level')[$i],
								'qty' => $this->input->post('qty')[$i],
								'where_use' => $this->input->post('use')[$i],
								'sub_con' => $this->input->post('sub_con')[$i],
								'po_no' => $this->input->post('po_no')[$i],
								'do_no' => $this->input->post('do_no')[$i],
								'created_by' => $_SESSION['username']['user_id'],
								'created_datetime' => date('Y-m-d h:i:s'),
								// 'status'=>$res
							);
							$k++;
							$this->Purchase_model->add_items($data_opt);

							// print_r($supplier);
						}
					}
					// exit();
					$Return['result'] = $this->lang->line('xin_success_purchase_requistion_added');
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
				$this->output($Return);
				exit;
			}
			//}

		}
	}
	public function purchase_requistion_list()
	{

		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['all_customers'] = $this->db->get('xin_employees')->result();
		$data['all_products'] = $this->Product_model->get_products()->result();
		// $data['all_suppliers'] = $this->Xin_model->all_suppliers();
		// $data['all_countries'] = $this->Xin_model->get_countries();
		// $data['all_packings'] = $this->Xin_model->get_packing_type();
		// $data['get_gst'] = $this->Xin_model->get_gst();
		// $data['all_payment_terms'] = $this->Xin_model->get_payment_term();

		if (!empty($session)) {
			$this->load->view("admin/purchase/purchase_requistion_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		// $this->Purchase_model->purchase_requistion_list();
		$purchase = $this->db->select('pr.*,xin_employees.first_name,xin_employees.last_name,p.project_title,pr.status as pstatus,milestone_categories.milestonecategory_title,tasks.task_title')
			->join('xin_employees', 'pr.created_by=xin_employees.user_id')
			->join('projects p', 'p.project_id=pr.project_id', 'left')
			->join('milestone_categories', 'pr.mile_stone=milestone_categories.milestonecategory_id', 'left')
			->join('tasks', 'pr.task=tasks.task_id', 'left')
			->get('purchase_requistion pr');
		$data = array();


		$i = 0;
		foreach ($purchase->result() as $r) {
			$i++;

			$view = '';
			$edit = '';
			$delete = '';


			$invoice = '<span data-toggle="tooltip" data-placement="top" title="Download"><a href="' . site_url() . 'admin/Purchase/preq_invoice_pdf/' . $r->purchase_requistion_id  . '" target="_blank"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

			if (in_array('2905', $role_resources_ids)) {
				$view = '<span data-toggle="tooltip" data-placement="top" title="View"><a href="' . site_url() . 'admin/Purchase/view_pr/' . $r->purchase_requistion_id  . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
			}
			if (in_array('2903', $role_resources_ids)) {
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-purchase_requistion_id="' . $r->purchase_requistion_id  . '"><span class="fa fa-pencil"></span></button></span>';
			}
			if (in_array('2904', $role_resources_ids)) {
				$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->purchase_requistion_id  . '"><span class="fa fa-trash"></span></button></span>';
			}

			$conf = '<span data-toggle="tooltip" data-placement="top" title="Confirm"><button type="button" class="btn icon-btn btn-xs btn-primary waves-effect waves-light confirms1" data-toggle="modal" data-target=".view-modal-data" data-purchase_requistion_id="' . $r->purchase_requistion_id . '">Convert PO</button></span>';

			if ($r->status == "Approved") {
				$combhr = $view . $delete . $invoice;
			} else if ($r->status == "Rejected") {
				$combhr = $view . $delete;
			}
			// else if($r->status == "Approved"){
			// 	$combhr = $view . $delete ;

			// }
			else {
				$combhr = $edit . $view . $delete;
			}

			$status = $r->pstatus;

			if ($status == 'Rejected') {
				$status = '<span style="color: red; font-weight: bold;">' . $status . '</span>';
			} elseif ($status == 'Approved') {
				$status = '<span style="color: blue; font-weight: bold;">' . $status . '</span>';
			} elseif ($status == 'Pending Project Manager Approval' || $status == 'Pending Engineer Verification') {
				$status = '<span style="color: green; font-weight: bold;">' . $status . '</span>';
			}
			// Else, leave as is

			$data[] = array(
				$i,
				$combhr,
				$r->porder_id,
				date('d-m-Y', strtotime($r->created_datetime)),
				$r->first_name . " " . $r->last_name,
				$r->milestonecategory_title,
				$r->task_title,
				$status
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $purchase->num_rows(),
			"recordsFiltered" => $purchase->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	public function events($mrf_id, $mrf_no, $project_id)
	{
		// Prepare the data array
		$data = [
			'event_creatorid' => $_SESSION['username']['user_id'], // Replace with your session logic
			'event_item' => 'hrms/admin/Purchase/view_pr/',
			'event_item_id' => $mrf_id,
			'event_item_lang' => $mrf_no . 'MRF Created',
			'event_item_content' => $mrf_id, // Use CI3's language system
			'event_item_content2' => '',
			'event_parent_type' => 'MRF',
			'event_parent_id' => $mrf_id,
			'event_parent_title' => ($project_id) ? $project_id : '',
			'event_clientid' => '',
			'event_show_item' => 'yes',
			'event_show_in_timeline' => 'yes',
			'eventresource_type' => '',
			'eventresource_id' => '',
			'event_notification_category' => 'notifications_billing_activity',
		];

		// Record the event
		// $event_id = $this->Event_model->create_event($data); // Assuming create_event is a method in your model
		$this->db->insert('events', $data);
		$event_id = $this->db->insert_id();
		if ($event_id) {
			// Get users (main client)
			// $users = $this->User_model->get_client_users($estimate->bill_clientid, 'owner', 'ids');

			// Record the notification
			$eventtracking_data = [
				'eventtracking_eventid' => $event_id,
				'eventtracking_userid' => $_SESSION['username']['user_id'], // Replace with your session logic
				'eventtracking_source' => $data['event_item'],
				'eventtracking_source_id' => $data['event_item_id'],
				'eventtracking_status' => 'unread',
				'parent_type' => $data['event_parent_type'],
				'parent_id' => $data['event_parent_id'],
				'resource_type' => $data['eventresource_type'],
				'resource_id' => $data['eventresource_id'],
			];

			// $this->Eventtracking_model->save_eventtracking($eventtracking_data); // Assuming save_eventtracking is a method in your model
			$this->db->insert('events_tracking', $eventtracking_data);
		}
	}
	public function rej_pr()
	{

		$id = $this->input->post('purchase_requistion_id');
		// print_r($id);exit;
		$check = $this->db->update('purchase_requistion', ['status' => 'Rejected'], ['purchase_requistion_id' => $id]);
		if ($check) {
			$Return['result'] = "Material Requisition Form (MRF) Rejected";
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;

		// print_r($id);exit;

	}
	public function change_status()
	{
		$id = $this->input->post('purchase_requistion_id');
		$status = $this->input->post('status');
		// print_r($id);exit;
		$data = array(
			'status' => $status
		);
		$check = $this->Purchase_model->update_status($data, $id);
		if ($check) {
			$Return['result'] = "Material Requisition Form (MRF) status updated";
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
	}
	public function view_pr($id)
	{
		$session = $this->session->userdata('username');

		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (empty($session)) {
			redirect('admin/');
		}

		// $data['title'] = $this->Xin_model->site_title();
		// $data['breadcrumbs'] = 'Purchase Requsition View';
		// $data['path_url'] = 'purchase_order';
		$result = $this->Purchase_model->read_purchase_requistion($id);
		$supervisor_signature = [];
		$engineer_data = [];

		if (!empty($result) && isset($result[0])) {
			$supervisor_signature = $this->db->select('signature')->where('user_id', $result[0]->supervisor)->get('xin_employees')->result();

			if (isset($result[0]->eng_status) && $result[0]->eng_status == "Yes") {
				$engineer_data = $this->db->select('signature,first_name,last_name')->where('user_id', $result[0]->eng_id)->get('xin_employees')->result();
			}
		}

		$data = array(
			'title' => 'Material Requisition Form (MRF) | ' . $this->Xin_model->site_title(),
			'breadcrumbs' => 'Purchase Requsition View',
			'purchase_requistion_id' => $result[0]->purchase_requistion_id ?? null,
			'porder_id' => $result[0]->porder_id ?? null,
			'path_url' => 'purchase_requistion',
			'project_id' => $result[0]->project_id ?? null,
			'supervisor_signature' => $supervisor_signature[0]->signature ?? 'No Signature',
			'engineer_signature' => isset($engineer_data[0]->signature) ? $engineer_data[0]->signature : 'No Signature',
			'engineer_name' => isset($engineer_data[0]->first_name, $engineer_data[0]->last_name) ? $engineer_data[0]->first_name . " " . $engineer_data[0]->last_name : '--',
			'engineer_date' => $result[0]->eng_date,
			'eng_status' => $result[0]->eng_status,
			'projm_status' => $result[0]->projm_status,
			'management_status' => $result[0]->management_status,
			'project_name' => $result[0]->project_title,
			'milestone' => $result[0]->mile_stone,
			'description_name' => $result[0]->description,
			// 'supplier_id' => explode(',', $result[0]->supplier_id),
			'customer_id' => $result[0]->customer_id,
			'site_address' => $result[0]->address1,
			// 'required_date' => $result[0]->required_date,
			'location' => $result[0]->location,
			'mrf_no' => $result[0]->mrf_no,
			'order_date' => $result[0]->order_date,
			'site' => $result[0]->site,
			'crane' => $result[0]->crane,
			'others' => $result[0]->others,

			'supervisor' => $result[0]->supervisor_first_name . " " . $result[0]->supervisor_last_name,
			'sub_contractor' => $result[0]->subcontractor_first_name,
			'earliest_date' => $result[0]->earliest_date,
			'latest_date' => $result[0]->latest_date,
			'approvers_name' => $result[0]->approver_first_name . " " . $result[0]->approver_last_name,
			'approvers_signature' => $result[0]->approver_signature,
			'all_pp' => $this->db->get('purchase_purpose')->result(),
			'pp' => $result[0]->purchase,
			'status' => $result[0]->pstatus,
			'status_reason' => $result[0]->status_reason,

			'all_products' => $this->db->get('product')->result(),
			// 'all_items' => $this->db->where('purchase_requistion_id', $id)->get('purchase_requistion_item_mapping')->result(),
			// purchase_requistion_item_mapping.sup_apr,
			'all_items' => $this->db->select('purchase_requistion.*,
			purchase_requistion_item_mapping.feed,
			purchase_requistion_item_mapping.apr,
			purchase_requistion_item_mapping.level,
			purchase_requistion_item_mapping.where_use,
			purchase_requistion_item_mapping.sub_con,
			purchase_requistion_item_mapping.po_no,
			purchase_requistion_item_mapping.do_no,
			purchase_requistion_item_mapping.uom,
			product.product_name,
			product.description,
			product.size,
			purchase_requistion_item_mapping.qty,
			purchase_requistion_item_mapping.remark')
				->from('purchase_requistion')
				->join('purchase_requistion_item_mapping', 'purchase_requistion.purchase_requistion_id=purchase_requistion_item_mapping.purchase_requistion_id')
				->join('product', 'purchase_requistion_item_mapping.product_id=product.product_id')
				->where('purchase_requistion.purchase_requistion_id', $id)->get()->result(),
			// 'is_gst_inclusive' => $result[0]->is_gst_inclusive,
			// 'payment_term_id' => $result[0]->payment_term_id,
			// 'term_condition' => $result[0]->term_condition_description,
			'get_all_project' => $this->Xin_model->get_all_project(),
			'get_all_customer' =>  $this->db->get('xin_employees')->result(),
			'settings' => $this->Xin_model->read_company_setting_info(1),
			'invoice_settings' => $this->Xin_model->read_setting_info(1),
			'all_customers' =>  $this->db->get('xin_employees')->result(),
			'button' =>  '<span data-toggle="tooltip" data-placement="top" title="Confirm"><button type="button" class="btn icon-btn btn-xs btn-primary waves-effect waves-light " data-toggle="modal" data-target=".view-modal-data" data-purchase_requistion_id="' . $result[0]->purchase_requistion_id . '">Confirm</button></span>',
			'EngineerButton' =>  '<span data-toggle="tooltip" data-placement="top" title="Engineer Confirm">
    <button type="button" class="btn btn-success statusbutton" data-url="' . site_url() . 'admin/Purchase/MRFConf/' . $result[0]->purchase_requistion_id  . '?type=engineer">Engineer Confirm</button></span>',

			'ProjectManagerButton' =>  '<span data-toggle="tooltip" data-placement="top" title="Project Manager Confirm">
    <button type="button" class="btn btn-info statusbutton" data-url="' . site_url() . 'admin/Purchase/MRFConf/' . $result[0]->purchase_requistion_id  . '?type=projmanager">Project Manager Confirm</button></span>',

			'ManagementButton' =>  '<span data-toggle="tooltip" data-placement="top" title="Management Confirm">
    <button type="button" class="btn btn-warning statusbutton" data-url="' . site_url() . 'admin/Purchase/MRFConf/' . $result[0]->purchase_requistion_id  . '?type=management">Management Confirm</button></span>',


		);
		// echo "<pre>";
		// print_r($data['all_items']);exit;

		if (!empty($session)) {
			$data['subview'] = $this->load->view("admin/purchase/purchase_req_view", $data, TRUE);
			// $this->load->view("admin/purchase/purchase_req_view", $data);
			$this->load->view('admin/layout/pms/layout_pms', $data); //page load
		} else {
			redirect('admin/');
		}
	}

	public function MRFConf($id)
	{
		// print_r();exit;
		$type = $this->input->get('type');
		$check = false; // Initialize $check with a default value

		if ($type == 'engineer') {
			$check = $this->db->update('purchase_requistion', ['status' => 'Approved by Engineer', 'eng_status' => 'Yes', 'eng_id' => $_SESSION['username']['user_id'], 'eng_date' => date('d-m-Y')], ['purchase_requistion_id' => $id]);
		} else if ($type == 'projmanager') {
			$check = $this->db->update('purchase_requistion', ['status' => 'Approved by Project Manager', 'projm_status' => 'Yes', 'projm_id' => $_SESSION['username']['user_id'], 'projm_date' => date('d-m-Y')], ['purchase_requistion_id' => $id]);
		} else if ($type == 'management') {
			$check = $this->db->update('purchase_requistion', ['status' => 'Approved by Management Team', 'management_status' => 'Yes', 'manage_id' => $_SESSION['username']['user_id'], 'manager_date' => date('d-m-Y')], ['purchase_requistion_id' => $id]);
		}

		if ($check) {
			$Return['result'] = "MRF Approved";
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;

		// print_r($id);exit;

	}

	public function read_pr()
	{
		$session = $this->session->userdata('username');
		$id = $_GET['purchase_requistion_id'];
		// $id=$this->uri->segment(4);
		$data['project_id'] = $this->db->select('project_id')->from('purchase_requistion')->where('purchase_requistion_id', $id)->get()->result();
		$data['all_items'] = $this->db->where('purchase_requistion_id', $id)->get('purchase_requistion_item_mapping')->result();
		$data['all_products'] = $this->db->get('product')->result();
		$data['all_supplier'] = $this->db->get('xin_suppliers')->result();
		if (!empty($session)) {
			$this->load->view('admin/purchase/dialog_confirm_pur_req', $data);
		} else {
			redirect('admin/');
		}
	}
	public function read()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('purchase_requistion_id');
		$result = $this->Purchase_model->read_purchase_requistion($id);


		$data = array(
			'purchase_requistion_id' => $result[0]->purchase_requistion_id,
			'porder_id' => $result[0]->porder_id,
			// 'warehouse_id' => $result[0]->warehouse_id,
			'project_id' => $result[0]->project_id,
			'milestone' => $result[0]->mile_stone,
			'description_name' => $result[0]->task,
			// 'supplier_id' => explode(',', $result[0]->supplier_id),
			'customer_id' => $result[0]->customer_id,
			'site_address' => $result[0]->address1,
			'crane' => $result[0]->crane,
			'others' => $result[0]->others,

			// 'required_date' => $result[0]->required_date,
			'location' => $result[0]->location,
			'mrf_no' => $result[0]->mrf_no,
			'order_date' => $result[0]->order_date,
			'site' => $result[0]->site,
			'supervisor' => $result[0]->supervisor,
			'sub_contractor' => $result[0]->sub_contractor,
			'earliest_date' => $result[0]->earliest_date,
			'latest_date' => $result[0]->latest_date,
			'status' => $result[0]->status,
			'status_reason' => $result[0]->status_reason,

			'all_pp' => $this->db->get('purchase_purpose')->result(),
			'pp' => $result[0]->purchase,
			'status' => $result[0]->pstatus,
			'all_products' => $this->db->get('product')->result(),
			'all_items' => $this->db->where('purchase_requistion_id', $id)->get('purchase_requistion_item_mapping')->result(),
			// 'is_gst_inclusive' => $result[0]->is_gst_inclusive,
			// 'payment_term_id' => $result[0]->payment_term_id,
			// 'term_condition' => $result[0]->term_condition_description,
			'get_all_project' => $this->Xin_model->get_all_project(),
			'get_all_customer' =>  $this->db->get('xin_employees')->result(),
			'settings' => $this->Xin_model->read_company_setting_info(1),
			'invoice_settings' => $this->Xin_model->read_setting_info(1),
			'all_customers' =>  $this->db->get('xin_employees')->result(),
			'all_subcontractors' => $this->db->where('subcon_supplier', 'Yes')->get('xin_suppliers')->result()

		);
		//echo "<pre>"; print_r($data);exit;

		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/purchase/dialog_purchase_requistion', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update()
	{

		if ($this->input->post('edit_type') == 'edit_purchase_requistion') {
			$id = $this->uri->segment(4);

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			// if($this->input->post('u_project_id')==='') {
			// 	$Return['error'] = $this->lang->line('error_project_field');
			// } else 
			if ($this->input->post('u_project_id') == '') {
				$Return['error'] = 'Project Name is required field';
			} else if ($this->input->post('u_order_date') == '') {
				$Return['error'] = 'MRF date is required field';
			} else if ($this->input->post('milestone_id1') == '') {
				$Return['error'] = 'Select Milestone';
			
			// else if ($this->input->post('task_id1') == '') {
			// 	$Return['error'] = 'Select Task';
			} else if (count($this->input->post('u_product_id')) === 0) {
				for ($i = 0; $i < count($this->input->post('u_product_id')); $i++) {
					if ($this->input->post('u_product_id')[$i] != "") {
						$Return['error'] = "Add Items";
					}
				}
			} else if (count($this->input->post('u_product_id')) > 0) {
				for ($i = 0; $i < count($this->input->post('u_product_id')); $i++) {
					if ($this->input->post('u_product_id')[$i] == "") {
						$Return['error'] = "Add Items";
					}
				}
			}

			// else if ($this->input->post('u_project_id') == '') {
			// 	$Return['error'] = "Select Project";
			// }

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			if ($this->input->post('u_check1') != "") {
				$u_check = implode(',', $this->input->post('u_check1'));
			} else {
				$u_check = '';
			}
			if ($this->input->post('u_chk_site') != "") {
				$u_location = implode(',', $this->input->post('u_chk_site'));
			} else {
				$u_location = "";
			}
			$data = [
				// 'customer_id' => $this->input->post('u_customer_id'),
				// // 'required_date' => $this->input->post('u_date_required'),
				// 'purchase' => $this->input->post('pp'),
				// 'location' => $this->input->post('location1'),
				// 'site_address' => $this->input->post('u_site_address1'),
				'project_id' => $this->input->post('u_project_id'),
				'location'	=> $u_location,
				'mrf_no'	=> $this->input->post('u_mrf_no'),
				'mile_stone'	=> $this->input->post('milestone_id1'),
				'task'	=> $this->input->post('task_id1'),
				'order_date'	=> $this->input->post('u_order_date'),
				'site'	=> $u_check,
				'crane' => ($this->input->post('crane1') == 'others') ? 0 : $this->input->post('crane1'),
				'others' => ($this->input->post('crane1') == 'others') ? $this->input->post('other_crane1') : '',
				'supervisor'	=> $this->input->post('u_supervisor'),
				'sub_contractor'	=> $this->input->post('u_sub_contractor'),
				'earliest_date'	=> $this->input->post('u_earliest_date'),
				'latest_date'	=> $this->input->post('u_latest_date'),
				'site_address'	=> ($this->input->post('u_site_address_select')=='others')?$this->input->post('other_u_site_address'):$this->input->post('u_site_address_select'),

				'status' => $this->input->post('u_status'),
				'status_reason' => $this->input->post('u_status_reason'),

			];

			// print_r($data);
			$id = $this->input->post('purchase_requistion_id');
			// print_r($iid);exit;



			if ($this->input->post('u_status') == 'Engineer Confirmation') {
				$data['eng_status'] = "Yes";
				$data['eng_date'] = date('d-m-Y');
				$data['eng_id'] = $_SESSION['username']['user_id'];
			}

			$result = $this->Purchase_model->update_status($data, $id);
			// $this->Purchase_model->update($data, $iid);

			if ($result) {
				if (count($this->input->post('u_product_id')) > 0) {
					$this->Purchase_model->delete_item_record($id);
					$k = 1;
					for ($i = 0; $i < count($this->input->post('u_product_id')); $i++) {
						// $supplier = $this->input->post('supplier_id' . $k);

						$data_opt = array(
							'purchase_requistion_id' => $id,
							'product_id' => $this->input->post('u_product_id')[$i],
							'qty' => $this->input->post('u_qty')[$i],
							'level' => $this->input->post('u_level')[$i],
							'where_use' => $this->input->post('u_use')[$i],
							'sub_con' => $this->input->post('u_sub_con')[$i],
							'po_no' => $this->input->post('u_po_no')[$i],
							'do_no' => $this->input->post('u_do_no')[$i],

							// 'status'=>$res
						);
						$k++;
						$this->Purchase_model->add_items($data_opt);
						// print_r($supplier);
					}
				}
				$Return['result'] = $this->lang->line('xin_success_purchase_requistion_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	public function conf_req()
	{

		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $_POST['ext_name'];

		if (empty($this->input->post('u_supplier'))) {
			$Return['error'] = "Select Supplier";
			$this->output($Return);
			exit;
		}

		$dataforpo1 = $this->db->where('purchase_requistion_id', $id)->get('purchase_requistion_item_mapping')->result();
		$dataforpo = $this->db->where('purchase_requistion_id', $id)->get('purchase_requistion')->result();
		$data1 = [];
		$v1 = 0;
		foreach ($this->input->post('u_supplier') as $index => $supplier) {


			if (empty($this->input->post('item_status')[$index])) {
				$Return['error'] = "Select Status";
				$this->output($Return);
				exit;
			}

			if ($this->input->post('supplier_warehouse_type')[$index] == 'supplier' && $this->input->post('item_status')[$index] == 'Accepted') {

				if ($this->input->post('u_warehouse_')[$index] != 0 || $this->input->post('u_warehouse_')[$index] != '') {

					$ware_qty = $this->db->where('prd_id', $this->input->post('u_item')[$index])
						->where('warehouse_id', $this->input->post('u_warehouse_')[$index])
						->get('stock_management')
						->row(); // Assuming only one result is expected

					if ($ware_qty) {
						// Calculate the new stock quantity after deduction
						$new_stock_qtn = (int)$ware_qty->quantity - (int)$this->input->post('u_apr')[$index];

						if ($this->input->post('u_apr')[$index] > $ware_qty->quantity) {
							$new_sup_qtn = (int)$this->input->post('u_apr')[$index] - (int)$ware_qty->quantity;
						} else {
							$new_sup_qtn = (int)$ware_qty->quantity - (int)$this->input->post('u_apr')[$index];
						}

						// Ensure the stock quantity doesn't go below 0
						$new_stock_qtn = max($new_stock_qtn, 0);

						// Update the stock quantity in the database
						$update_ware_qty = $this->db->update(
							'stock_management',
							['quantity' => $new_stock_qtn],
							['prd_id' => $this->input->post('u_item')[$index], 'warehouse_id' => $this->input->post('u_warehouse_')[$index]]
						);

						$stock_move_data = array(
							'product_id' => $this->input->post('u_item')[$index],
							'remark' => $this->input->post('u_remark')[$index],
							'qtn' => $this->input->post('u_apr')[$index],
							'prj_id' => $dataforpo[0]->project_id,
							'wh_id' => $this->input->post('u_warehouse_')[$index],
							'trans_type' => 'OUTBOUND',
							'movement_type' => 'Issue',
							'stock_from' =>	 $this->input->post('u_warehouse_')[$index],
							'stock_to' => $dataforpo[0]->project_id,
							'from_to_type' => "warehouse to project",
							'created_date' => date('Y-m-d H:i:s'),
							'by_whome' => $_SESSION['username']['user_id']
						);
						$this->db->insert('stock_move_log', $stock_move_data);
					}
				}

				// print_r($new_sup_qtn);exit;


				// $abc = $this->db->select('def_gst_code')->from('xin_suppliers')->where('supplier_id', $supplier)->get()->result();
				// if (!empty($abc) && isset($abc[0]->def_gst_code)) {
				// 	// Extract numeric part from def_gst_code
				// 	$numeric_part = preg_replace('/[^0-9]/', '', $abc[0]->def_gst_code);
				// } else {
				$numeric_part = 0;
				// }

				if (in_array($this->input->post('u_supplier')[$index], $data1)) {
					$price = (float)$this->input->post('u_price')[$index];

					if ($this->input->post('u_warehouse_')[$index] != 0 || $this->input->post('u_warehouse_')[$index] != '') {
						// $quantity = $this->input->post('u_qty')[$index];
						if ($this->input->post('u_apr')[$index] > $this->input->post('u_qty')[$index]) {
							$quantity = (int)$this->input->post('u_apr')[$index] - (int)$this->input->post('u_qty')[$index];
						} else {
							$quantity = (int)$this->input->post('u_qty')[$index] - (int)$this->input->post('u_apr')[$index];
						}
					} else {
						$quantity =  (int)$this->input->post('u_qty')[$index];
					}


					$prd_total = ($price !== null && $quantity !== null) ? $price * $quantity : 0;


					$data_opt = [
						'porder_id' => $v1,
						'pur_req_id' => $id,
						'prd_id' => $this->input->post('u_item')[$index],
						'prd_color' => "#000000",
						'prd_color_name' => "Black",
						'supplier_id' => $supplier,
						'terms' => $this->input->post('terms')[$index],
						'sup_ref' => $this->input->post('sup_ref')[$index],
						'prd_price' => $price,
						'prd_qtn' => $quantity,
						'prd_total' => $prd_total,
						'type' => "product",
						'remark' => $this->input->post('u_remark')[$index],
						'created_by' => $_SESSION['username']['user_id'],
						'created_datetime' => date('Y-m-d h:i:s'),
					];


					// Insert into purchase_order_item_mapping table
					$confins = $this->db->insert('purchase_order_item_mapping', $data_opt);
					$this->db->update('xin_supplier_item_mapping', ['supplier_item_price' => $this->input->post('u_price')[$index]], ['supplier_item_name' => $this->input->post('u_item')[$index], 'supplier_id' => $supplier]);


					// $data_opt = array(

					// 	'items_no' => $this->input->post('u_item')[$index],
					// 	'from_ware' => ($this->input->post('u_warehouse_')[$index]) ?? 0,
					// 	'ware_item_qtn' => ($this->input->post('u_apr')[$index]) ?? 0,
					// 	'from_sup' => ($this->input->post('u_supplier')[$index]) ?? 0,
					// 	'item_qtn' => ($quantity) ?? 0,
					// 	'for_po' => $v1,
					// 	'date' => date('Y-m-d h:i:s'),
					// );
					// $this->db->insert('transaction', $data_opt);
				} else {
					if ($this->input->post('u_supplier')[$index] != NULL) {

						// print_r(count($this->input->post('u_supplier')));exit;
						$def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result();
						$get_sup_term = $this->db->select('supplier_terms')->from('xin_suppliers')->where('supplier_id', $this->input->post('u_supplier')[$index])->get()->result();
						$mile_task_id = $this->db->select('mile_stone,task')->from('purchase_requistion')->where('purchase_requistion_id ', $id)->get()->result();
						$podata = [
							'preq_id' => $id,
							'site_add' => $dataforpo[0]->site_address,
							'project_id' => $dataforpo[0]->project_id,
							'po_for' => ($dataforpo[0]->customer_id) ? $dataforpo[0]->customer_id : $_SESSION['username']['user_id'],
							'sub_total' => $this->input->post('grand_total'),
							'sup_ref' => $this->input->post('sup_ref')[$index],

							'gst' => $def_gst[0]->d_gst,
							'gst_amount' => $this->input->post('grand_total') * $def_gst[0]->d_gst / 100,
							'inclusive_gst' => 'on',
							'order_total' => $this->input->post('grand_total') + ($this->input->post('grand_total') * $def_gst[0]->d_gst / 100),
							// 'discount' => 0,
							'payment_term' => $get_sup_term[0]->supplier_terms,
							'po_terms' => '<ol>
												<li>Delivery should be more to the above contract or location specified unless otherwise.</li>
												<li>Supply shall be in accordance with instructions, no substitute parts will be accepted without prior approval.</li>
												<li>Delivery shall be on the delivery date stipulated above.</li>
												<li>The supplier shall inform us immediately, if for any reason, he is unable to adhere to these instructions.</li>
												<li>The Company will not be liable for any goods received unless this order carried an un authorised signature.</li>
										  </ol>',
							'status' => "Draft",
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
							'po_dates' => date('Y-m-d h:i:s'),
							'mile_stone' => $mile_task_id[0]->mile_stone,
							'task' => $mile_task_id[0]->task,
						];

						// Insert into purchase_order table
						$ins_id = $this->Purchase_model->add_order($podata);



						$v1 = $ins_id;

						if ($ins_id) {

							//Month Sequence PO Start
							$currentMonth = date('y/m', strtotime(date('Y-m-d')));

							// Handle sequence
							$this->db->trans_start(); // Start transaction
							$sequence = $this->Purchase_model->get_current_sequence($currentMonth);

							if ($sequence) {
								$new_sequence = $sequence->sequence + 1;
								$this->Purchase_model->update_sequence($currentMonth, $new_sequence);
							} else {
								$new_sequence = 1;
								$this->Purchase_model->insert_sequence($currentMonth, $new_sequence);
							}

							$this->db->trans_complete(); // Complete transaction

							$project_code = $this->Purchase_model->get_project_data($dataforpo[0]->project_id);
							if ($project_code[0]->project_code) {
								$supplier_name = $this->db->select('supplier_name')->from('xin_suppliers')->where('supplier_id', $supplier)->get()->result();
								$supplier_initial = strtoupper(substr($supplier_name[0]->supplier_name, 0, 2));
								$new_porder_id = "OIS/" . $supplier_initial . "/PD/" . $project_code[0]->project_code . "/" . $currentMonth . "/" . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);
								$job_up = $this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $ins_id]);
							} else {
								$supplier_name = $this->db->select('supplier_name')->from('xin_suppliers')->where('supplier_id', $supplier)->get()->result();
								$supplier_initial = strtoupper(substr($supplier_name[0]->supplier_name, 0, 2));
								$new_porder_id = "OIS/" . $supplier_initial . "/PD/" . $currentMonth . "/" . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);
								$job_up = $this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $ins_id]);
							}
						}



						$id = $_POST['ext_name'];

						$price = (float)$this->input->post('u_price')[$index];

						if ($this->input->post('u_warehouse_')[$index] != 0 || $this->input->post('u_warehouse_')[$index] != '') {

							if ($this->input->post('u_apr')[$index] > $this->input->post('u_qty')[$index]) {
								$quantity = (int)$this->input->post('u_apr')[$index] - (int)$this->input->post('u_qty')[$index];
							} else {
								$quantity = (int)$this->input->post('u_qty')[$index] - (int)$this->input->post('u_apr')[$index];
							}
						} else {
							$quantity =  (int)$this->input->post('u_qty')[$index];
						}



						$prd_total = ($price !== null && $quantity !== null) ? $price * $quantity : 0;

						$data_opt = [
							'porder_id' => $ins_id,
							'pur_req_id' => $id,

							'prd_id' => $this->input->post('u_item')[$index],
							'prd_uom_from_prq' => $this->input->post('prd_uom_from_prq')[$index],
							'supplier_id' => $supplier,
							'prd_color' => "#000000",
							'prd_color_name' => "Black",
							'prd_price' => $price,
							'prd_qtn' => $quantity,
							'prd_total' => $prd_total,
							'type' => "product",
							'terms' => $this->input->post('terms')[$index],
							'sup_ref' => $this->input->post('sup_ref')[$index],
							'remark' => $this->input->post('u_remark')[$index],
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						];



						// Insert into purchase_order_item_mapping table
						$confins = $this->db->insert('purchase_order_item_mapping', $data_opt);
						$this->db->update('xin_supplier_item_mapping', ['supplier_item_price' => $this->input->post('u_price')[$index]], ['supplier_item_name' => $this->input->post('u_item')[$index], 'supplier_id' => $supplier]);

						$this->db->delete('xin_supplier_item_mapping', ['supplier_id' => 0]);
					}
					array_push($data1, $this->input->post('u_supplier')[$index]);
				}
			}
		}

		$job = $this->db->update('purchase_requistion', ['apr_by' => $_SESSION['username']['user_id'], 'status' => "Approved", 'modified_datetime' => date('Y-m-d')], ['purchase_requistion_id' => $id]);
		if (count($this->input->post('u_item')) > 0) {
			$job = false; // Initialize the job variable to track if at least one update succeeds

			for ($i = 0; $i < count($this->input->post('u_item')); $i++) {
				if ($this->input->post('supplier_warehouse_type')[$i] == 'supplier' && $this->input->post('item_status')[$i] == 'Accepted') {
					if ((int)$this->input->post('u_qty')[$i] == (int)$this->input->post('u_apr')[$i]) {
						$supplier_apr_qtn = 0;
					} else {
						if ((int)$this->input->post('u_apr')[$i] > (int)$this->input->post('u_qty')[$i]) {
							$supplier_apr_qtn = (int)$this->input->post('u_apr')[$i] - (int)$this->input->post('u_qty')[$i];
						} else {
							$supplier_apr_qtn = (int)$this->input->post('u_qty')[$i] - (int)$this->input->post('u_apr')[$i];
						}
					}

					$data_opt = array(
						'qty' => $this->input->post('u_qty')[$i],
						'apr' => $this->input->post('u_apr')[$i],
						'sup_apr' => $supplier_apr_qtn,
						'feed' => $this->input->post('feed')[$i],
						'remark' => $this->input->post('u_remark')[$i],
						'item_status' => $this->input->post('item_status')[$i],
					);

					$update_result = $this->db->update('purchase_requistion_item_mapping', $data_opt, [
						'purchase_requistion_id' => $id,
						'product_id' => $this->input->post('u_item')[$i]
					]);

					if ($update_result) {
						$job = true; // If at least one update succeeds, set job to true
					}
				}
			}

			if ($job) {
				$Return['result'] = "Purchase Requisition Confirmed";
			} else {
				$Return['error'] = "Bug Something Went Wrong";
			}
		}

		// }

		$this->output($Return);
		exit;
	}

	function getPRDetails($id)
	{
		$res['pr_detail'] = $this->db->select('purchase_requistion.*,projects.project_title,xin_employees.first_name,xin_employees.last_name')
			->from('purchase_requistion')
			->join('purchase_requistion_item_mapping', 'purchase_requistion.purchase_requistion_id=purchase_requistion_item_mapping.purchase_requistion_id')
			->join('projects', 'purchase_requistion.project_id=projects.project_id')
			->join('xin_employees', 'purchase_requistion.customer_id=xin_employees.user_id')

			->where('purchase_requistion.purchase_requistion_id', $id)
			->get()->result();

		$res['con_pr_data'] = $this->db->select('conf_purchase_req.*,product.product_name,product.description,xin_suppliers.supplier_name')
			->from('conf_purchase_req')
			->join('purchase_requistion', 'conf_purchase_req.conf_pr_id=purchase_requistion.purchase_requistion_id')
			->join('product', 'conf_purchase_req.conf_item_id=product.product_id')
			->join('xin_suppliers', 'conf_purchase_req.conf_sup_id=xin_suppliers.supplier_id')
			->where('conf_purchase_req.conf_pr_id', $id)
			->get()->result();
		$res['all_products'] = $this->db->get('product')->result();
		//   print_r($res);exit;
		echo json_encode($res);
	}
	public function podetail($id)
	{
		$res = $this->db->where('product_id', $id)->get('product')->result();
		echo json_encode($res);
	}
	function getPODetails($id, $grn)
	{
		$this->load->library('session');
		if (!isset($_SESSION['username']['user_id'])) {
			echo json_encode(['error' => 'User not logged in']);
			return;
		}



		if ($id != 0) {
			$baseQuery = $this->db->select('
			purchase_order.*, 
			projects.project_id, 
			projects.project_title, 
			projects.warehouse_id, 
			xin_employees.first_name, 
			xin_employees.last_name, 
			xin_suppliers.supplier_name')
				->from('purchase_order')
				->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id', 'left')
				->join('projects', 'purchase_order.project_id=projects.project_id', 'left')
				->join('xin_suppliers', 'purchase_order_item_mapping.supplier_id=xin_suppliers.supplier_id', 'left')
				->join('xin_employees', 'purchase_order.po_for=xin_employees.user_id', 'left');
			$baseQuery->where('purchase_order.purchase_order_id', $id);
		} else if ($id == 0 && $grn != 0) {
			$baseQuery = $this->db->select('grn_tbl.*,
										  projects.project_id, 
										  projects.project_title, 
										  projects.project_address as site_add, 
										  projects.warehouse_id, 
										  xin_employees.first_name, 
										  xin_employees.last_name, 
										  xin_suppliers.supplier_name')
				->from('grn_tbl')
				->join('grn_item_mapping', 'grn_tbl.grn_id =grn_item_mapping.grn_id', 'left')
				->join('projects', 'grn_tbl.prjkt_id=projects.project_id', 'left')
				->join('xin_suppliers', 'grn_item_mapping.sup_id=xin_suppliers.supplier_id', 'left')
				->join('xin_employees', 'grn_tbl.created_by=xin_employees.user_id', 'left')
				->where('grn_tbl.grn_id', $grn);
		}

		$res['po_detail'] = $baseQuery->get()->result();
		$res['grn_check'] = $this->db->where('pur_order_id', $id)->get('grn_item_mapping')->result();
		$res['grn_check1'] = $this->db->where('po_number', $id)->get('grn_tbl')->result();
		$res['grn_log'] = $this->db->select('grn_log.*, product.product_name, product.description')
			->from('grn_log')
			->join('product', 'grn_log.item = product.product_id', 'left')
			->where_not_in('grn_log.qtn', 0)
			->where('grn_log.po', $id)
			->get()
			->result();

		$res['po_items'] = $this->db->select('purchase_order_item_mapping.*,product.product_name,product.description')
			->from('purchase_order_item_mapping')
			->join('product', 'purchase_order_item_mapping.prd_id=product.product_id', 'left')
			->where('purchase_order_item_mapping.porder_id', $id)
			->get()
			->result();

		$companyId = $this->db->select('company_id')
			->from('xin_employees')
			->where('user_id', $_SESSION['username']['user_id'])
			->get()
			->row('company_id');

		$res['warehouse_all'] = $this->db->where('org_id', $companyId)->get('warehouse')->result();

		echo json_encode($res);
	}

	public function get_product_details($id)
	{
		$result = $this->db->where(['product_id' => $id])
			->get('product')->result();
		echo json_encode($result);
	}
	public function get_product_qtn($product_id, $supplier_id)
	{
		// Get supplier item details
		$result = $this->db->select('product.prd_img,product.std_uom,xin_supplier_item_mapping.supplier_item_price,xin_suppliers.supplier_terms')->from('xin_supplier_item_mapping')
			->join('product', 'xin_supplier_item_mapping.supplier_item_name=product.product_id', 'left')
			->join('xin_suppliers', 'xin_supplier_item_mapping.supplier_id=xin_suppliers.supplier_id', 'left')
			->where(['xin_suppliers.supplier_id' => $supplier_id, 'supplier_item_name' => $product_id])->get()->result();

		// Check if result is not empty
		// if (!empty($result)) {
		// 	// Get warehouse quantity
		// 	$warehouse_qtn = $this->db->select('quantity as item_qty')->from('stock_management')->where('prd_id', $product_id)->get()->result();

		// 	// Append warehouse quantity to result
		// 	$result[0]->warehouse_qtn = $warehouse_qtn;
		// }

		echo json_encode($result);
	}
	function get_bill_address()
	{
		$address = $this->db->get('xin_supplier_billing')->result_array(); // Use result_array for associative array
		header('Content-Type: application/json'); // Explicitly set header
		echo json_encode($address);
	}
	function get_sup_bill($sid)
	{
		// Fetch supplier billing information
		$supplierBilling = $this->db->select('address, bill_id, supplier_id')
			->from('xin_supplier_billing')
			->where('supplier_id', $sid)
			->get()
			->result();

		// Combine the two into a structured response
		$response = [
			// Array of items
			'billing' => $supplierBilling, // Array of billing details
		];

		// Return the response as JSON
		echo json_encode($response);
	}
	public function get_sup_product($sid)
	{
		$data['title'] = $this->Xin_model->site_title();


		// Fetch supplier item mapping with products
		$supplierItems = $this->db->select('product_name,prd_img, product_id, description')
			->from('xin_suppliers')
			->join('xin_supplier_item_mapping', 'xin_supplier_item_mapping.supplier_id = xin_suppliers.supplier_id')
			->join('product', 'xin_supplier_item_mapping.supplier_item_name = product.product_id')
			->where('xin_supplier_item_mapping.supplier_id', $sid)
			->get()
			->result();

		// Fetch supplier billing information
		$supplierBilling = $this->db->select('address, bill_id, supplier_id')
			->from('xin_supplier_billing')
			->where('supplier_id', $sid)
			->get()
			->result();

		// Combine the two into a structured response
		$response = [
			'items' => $supplierItems,     // Array of items
			'billing' => $supplierBilling, // Array of billing details
		];

		// Return the response as JSON
		echo json_encode($response);
	}

	function preq_invoice_pdf($id)
	{
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$result = $this->Purchase_model->read_purchase_requistion($id);
			//echo $this->db->last_query();exit;
			$res = $this->db->select('first_name,last_name,signature')->from('xin_employees')->where('user_id', $result[0]->apr_by)->get()->result();

			$abc = $this->Xin_model->read_setting_info(1);
			$proc_logo = $this->db->get('xin_quo')->result();

			$image_path = base_url('uploads/logo/' . $abc[0]->invoice_logo); // Replace with the actual image path
			$image_path1 = base_url('uploads/quo/' . $proc_logo[0]->logo4); // Replace with the actual image path
			$signature = base_url('uploads/document/signature/' . $res[0]->signature);

			// $image_path = base_url() . 'uploads/logo/PCEC.JPG'; // Replace with the actual image path
			// Disable SSL verification
			$context = stream_context_create([
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
				],
			]);
			$image_data = file_get_contents($image_path, false, $context);
			$image_base64 = base64_encode($image_data);

			$image_data2 = file_get_contents($image_path1, false, $context);
			$image_base642 = base64_encode($image_data2);



			$image_data5 = file_get_contents($signature, false, $context);
			$signature = base64_encode($image_data5);

			// print_r($res);exit;


			// Access the 'logo1' property from the query result

			// $signature = base_url('uploads/document/signature/' . $res[0]->signature);


			// $image_data5 = file_get_contents($signature, false, $context);


			// $signature = base64_encode($image_data5);


			// $result = $this->Purchase_model->read_purchase_requistion($id);
			$data = array(
				// 'title' => 'Material Requisition Form (MRF) | ' . $this->Xin_model->site_title(),
				// 'breadcrumbs' => 'Purchase Requsition View',
				'purchase_requistion_id' => $result[0]->purchase_requistion_id,
				'porder_id' => $result[0]->porder_id,
				// 'path_url' => 'purchase_requistion',
				// 'warehouse_id' => $result[0]->warehouse_id,
				'project_id' => $result[0]->project_id,
				'project_name' => $result[0]->project_title,
				'signature' => $signature,
				'image_base64' => $image_base64,
				'image_base641' => $image_base642,
				// 'supplier_id' => explode(',', $result[0]->supplier_id),
				'customer_id' => $result[0]->customer_id,
				'site_address' => $result[0]->address1,
				// 'required_date' => $result[0]->required_date,
				'location' => $result[0]->location,
				'mrf_no' => $result[0]->mrf_no,
				'order_date' => $result[0]->order_date,
				'site' => $result[0]->site,
				'supervisor' => $result[0]->supervisor_first_name . " " . $result[0]->supervisor_last_name,
				'sub_contractor' => $result[0]->subcontractor_first_name,
				'earliest_date' => $result[0]->earliest_date,
				'latest_date' => $result[0]->latest_date,
				'approvers_name' => $result[0]->approver_first_name . " " . $result[0]->approver_last_name,
				'approvers_signature' => $result[0]->approver_signature,
				'all_pp' => $this->db->get('purchase_purpose')->result(),
				'pp' => $result[0]->purchase,
				'status' => $result[0]->pstatus,
				'status_reason' => $result[0]->status_reason,

				'all_products' => $this->db->get('product')->result(),
				// 'all_items' => $this->db->where('purchase_requistion_id', $id)->get('purchase_requistion_item_mapping')->result(),
				// purchase_requistion_item_mapping.sup_apr,
				'all_items' => $this->db->select('purchase_requistion.*,
				purchase_requistion_item_mapping.feed,
				purchase_requistion_item_mapping.apr,
				purchase_requistion_item_mapping.level,
				purchase_requistion_item_mapping.where_use,
				purchase_requistion_item_mapping.sub_con,
				purchase_requistion_item_mapping.po_no,
				purchase_requistion_item_mapping.do_no,
				product.product_name,
				product.description,
				product.size,
				purchase_requistion_item_mapping.qty,
				purchase_requistion_item_mapping.remark')
					->from('purchase_requistion')
					->join('purchase_requistion_item_mapping', 'purchase_requistion.purchase_requistion_id=purchase_requistion_item_mapping.purchase_requistion_id')
					->join('product', 'purchase_requistion_item_mapping.product_id=product.product_id')
					->where('purchase_requistion.purchase_requistion_id', $id)->get()->result(),
				// 'is_gst_inclusive' => $result[0]->is_gst_inclusive,
				// 'payment_term_id' => $result[0]->payment_term_id,
				// 'term_condition' => $result[0]->term_condition_description,
				'get_all_project' => $this->Xin_model->get_all_project(),
				'get_all_customer' =>  $this->db->get('xin_employees')->result(),

				'all_customers' =>  $this->db->get('xin_employees')->result(),
				'button' =>  '<span data-toggle="tooltip" data-placement="top" title="Confirm"><button type="button" class="btn icon-btn btn-xs btn-primary waves-effect waves-light " data-toggle="modal" data-target=".view-modal-data" data-purchase_requistion_id="' . $result[0]->purchase_requistion_id . '">Confirm</button></span>',
			);

			// print_r($data['product']);exit();
			$html = $this->load->view('admin/purchase/purchase_req_invoice', $data, true);
			// Create Dompdf instance with options
			$options = new Options();
			$options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
			// $options->set('margin_top', '10mm'); // Set top margin
			// $options->set('margin_right', '10mm'); // Set right margin
			// $options->set('margin_bottom', '10mm'); // Set bottom margin
			// $options->set('margin_left', '10mm'); // Set left margin
			$dompdf = new Dompdf($options);

			// Load HTML content
			$dompdf->loadHtml($html);

			// (Optional) Set paper size and orientation
			$dompdf->setPaper('A4', 'landscape');

			// Render PDF (output)
			$dompdf->render();

			// Output the PDF to the browser
			$dompdf->stream($result[0]->porder_id . ".pdf", ['Attachment' => false]);
		} else {
			redirect('admin/');
		}
	}
	public function rej_po()
	{

		$id = $this->input->post('purchase_order_id');
		$check = $this->db->update('purchase_order', ['status' => "Rejected"], ['purchase_order_id' => $id]);
		if ($check) {
			$Return['result'] = "Purchase Order Rejected";
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;

		// print_r($id);exit;

	}

	public function con_po()
	{

		$id = $this->input->post('purchase_order_id');
		$prjkt_id = $this->db->select('project_id')->from('purchase_order')->where('purchase_order_id', $id)->get()->result();
		$check = $this->db->update('purchase_order', ['status' => 'Approved', 'approved_by' => $_SESSION['username']['user_id']], ['purchase_order_id' => $id]);

		$order = $this->db->select('purchase_order.*, purchase_order_item_mapping.*')  // Include GRN data
			->from('purchase_order')
			->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id = purchase_order_item_mapping.porder_id', 'left')
			// ->join('grn_table', 'purchase_order.purchase_order_id = grn_table.purchase_order_id', 'left')  // Assuming GRN table is linked with purchase order ID
			->where('purchase_order.purchase_order_id', $id)
			->group_start()  // Start grouping the WHERE conditions
			// ->where('purchase_order_item_mapping.type', 'blank')
			->or_where('purchase_order_item_mapping.type', 'product')
			->group_end()  // End the grouping
			->get();



		$po_num = $order->num_rows();

		if ($po_num > 0) {
			// Insert data into grn table
			$grn_data = [
				'prjkt_id' => $prjkt_id[0]->project_id,
				'po_number' => $id,
				'date' => date('Y-m-d'),
				'created_by' => $_SESSION['username']['user_id'],
				'created_datetime' => date('Y-m-d H:i:s'),
			];

			$grn_id = $this->Purchase_model->add_grn_data($grn_data); // Get the inserted GRN ID
			$currentMonth = date('ym');

			$this->db->trans_start(); // Start transaction

			// Check if sequence for the current month exists
			$sequence = $this->Purchase_model->get_grn_sequence($currentMonth);

			if ($sequence) {
				// Increment sequence
				$new_sequence = $sequence->sequence + 1;
				$this->Purchase_model->update_grn_sequence($currentMonth, $new_sequence);
			} else {
				// Initialize sequence for the new month
				$new_sequence = 1;
				$this->Purchase_model->insert_grn_sequence($currentMonth, $new_sequence);
			}

			$this->db->trans_complete(); // Complete transaction

			// Generate the new porder_id
			$new_grn_no = "OIS/GRN/" . $currentMonth . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);

			// $this->db->update('purchase_requistion', ['porder_id' => $new_pr_id], ['purchase_requistion_id' => $result]);

			// Update the grn_tbl with the new grn_no
			$this->db->update('grn_tbl', ['grn_no' => $new_grn_no], ['grn_id' => $grn_id]);


			// Loop through each item and insert into grn_item table
			$po_result = $order->result();
			foreach ($po_result as $item) {

				$grn_item_data = [
					'pur_order_id' => $id,
					'prjct_id' => $item->project_id,
					'prd_id' => ($item->prd_id) ?? 0,
					'blank_descr' => ($item->description) ?? '',
					'blank_unit' => ($item->unit) ?? '',
					'grn_id' => $grn_id,
					'sup_id' => $item->supplier_id,
					'qty_need' => $item->prd_qtn,
					'qty_rem' => $item->prd_qtn,
					'type' => $item->type,
					'created_by' => $_SESSION['username']['user_id'],
					'created_datetime' => date('Y-m-d H:i:s'),
				];


				$this->db->insert('grn_item_mapping', $grn_item_data);
			}
		}

		if ($check) {

			$Return['result'] = "Purchase Order Approved";
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;

		// print_r($id);exit;

	}


	// public function get_stock_qtn($prd_id){
	// 	$dataforpo1 = $this->db->select('stock_qtn')->where('product_id', $prd_id)->get('product_id')->result();
	// 	echo json_encode($dataforpo1);

	// }

	public function get_product_detail()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->uri->segment(4);

		// xin_suppliers.emps_id,
		$result = $this->db->select('product.*,
									xin_suppliers.supplier_name,
									xin_suppliers.supplier_id,
									xin_supplier_item_mapping.supplier_item_price,
									xin_suppliers.supplier_terms
								')
			->from('product')
			->join('xin_supplier_item_mapping', 'product.product_id=xin_supplier_item_mapping.supplier_item_name', 'left')
			->join('xin_suppliers', 'xin_supplier_item_mapping.supplier_id=xin_suppliers.supplier_id', 'left')
			->where('product.product_id', $id)
			->get()->result();

		// foreach ($result as $r) {
		// 	if ($r->emps_id == '') {
		// 		$r->temp_name = $r->supplier_name;
		// 	} else {
		// 		$xyz = $this->db->select('first_name, last_name')
		// 			->from('xin_employees')
		// 			->where('user_id', $r->emps_id)
		// 			->get()
		// 			->result();

		// 		// Check if any records are found
		// 		if (!empty($xyz)) {
		// 			// Make sure $xyz array has at least one element
		// 			if (isset($xyz[0]->first_name) && isset($xyz[0]->last_name)) {
		// 				$r->temp_name = $xyz[0]->first_name . " " . $xyz[0]->last_name;
		// 			} else {
		// 				// Handle the case where first_name or last_name is not set
		// 				$r->temp_name = ''; // Or any default value you prefer if first_name or last_name is not set
		// 			}
		// 		} else {
		// 			$r->temp_name = ''; // Or any default value you prefer if no records are found
		// 		}
		// 	}
		// }


		echo json_encode($result);
	}



	public function get_warehouses($product_id, $proj_id)
	{
		$this->db->select('warehouse.w_id, warehouse.w_name, stock_management.quantity');
		$this->db->from('stock_management');
		$this->db->join('warehouse', 'stock_management.warehouse_id = warehouse.w_id', 'left');

		// Add conditions
		$this->db->where('prd_id', $product_id);
		$this->db->where("warehouse.w_id NOT IN (SELECT warehouse_id FROM projects WHERE projects.warehouse_id = warehouse.w_id AND projects.project_id = $proj_id)");
		$this->db->where('warehouse.w_type', 'Company'); // Additional condition

		$warehouses = $this->db->get()->result();
		echo json_encode($warehouses);
	}


	public function check_product_existence($product_id, $quantity)
	{

		// Sum the quantities of the product from all warehouses
		$total_quantity = $this->db->select_sum('quantity')
			->where('prd_id', $product_id)
			->get('stock_management')
			->row()
			->quantity;
		// print_r($total_quantity);exit;
		// Check if the total quantity in warehouses is greater than or equal to the requested quantity
		$exists = $total_quantity >= $quantity;

		echo json_encode(['exists' => $exists, 'total_quantity' => $total_quantity]);
	}

	public function delete()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Purchase_model->delete_record($id);
		$this->Purchase_model->delete_item_record($id);

		if (isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_purchase_requistion_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
	public function get_supplier_address()
	{

		$data['title'] = $this->Xin_model->site_title();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$id = $this->uri->segment(4);

		$data = array(
			'supplier_id' => $id
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/purchase/get_supplier_address", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}

	public function purchase_order_list()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}

		$data['title'] = $this->Xin_model->site_title();

		$data['all_products'] = $this->db->get('product')->result();

		if (!empty($session)) {
			$this->load->view("admin/purchase/purchase_order_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		// xin_employees.first_name AS employee_first_name,
		// xin_employees.last_name AS employee_last_name,
		// xin_suppliers.emps_id,
		$edit = '';
		$delete = '';
		$view = '';
		$download = '';
		$payable = '';
		$expense = '';

		$purchase = $this->db->select('purchase_order.*, 
		xin_suppliers.supplier_name,
		purchase_requistion.porder_id as MRF_no,
		xin_employees.first_name,
		xin_employees.last_name,
		xin_payable.invoice_no,
		projects.project_title,
		projects.project_code,
		GROUP_CONCAT(DISTINCT product.product_name SEPARATOR ", ") as product_names, 
		purchase_requistion.created_datetime as mrf_date
		')
			->from('purchase_order')
			->join('purchase_requistion', 'purchase_order.preq_id=purchase_requistion.purchase_requistion_id ', 'left')
			->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id', 'left')
			->join('product', 'purchase_order_item_mapping.prd_id=product.product_id', 'left')
			->join('xin_suppliers', 'purchase_order_item_mapping.supplier_id=xin_suppliers.supplier_id', 'left')
			->join('projects', 'purchase_order.project_id=projects.project_id', 'left')
			->join('xin_payable', 'purchase_order.purchase_order_id=xin_payable.purchase_order_id', 'left')
			->join('xin_employees', 'purchase_order.po_for = xin_employees.user_id', 'left') // Conditional join for employee name
			->order_by('purchase_order.purchase_order_id', 'desc')
			->group_by('purchase_order.purchase_order_id')
			->get();


		$role_resources_ids = $this->Xin_model->user_role_resource();



		// $this->Purchase_model->purchase_order_list();
		// print_r($purchase->result());exit;
		$data = array();

		$i = 0;

		foreach ($purchase->result() as $r) {
			$i++;

			if (in_array('2909', $role_resources_ids)) {
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-purchase_order_id="' . $r->purchase_order_id   . '"><span class="fa fa-pencil"></span></button></span>';
			}
			if (in_array('2910', $role_resources_ids)) {
				$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->purchase_order_id   . '"><span class="fa fa-trash"></span></button></span>';
			}
			$download = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a target="_blank" href="' . site_url() . 'admin/purchase/new_pdf_create/' . $r->purchase_order_id  . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			if (in_array('2911', $role_resources_ids)) {
				$view = '<span data-toggle="tooltip" data-placement="top" title="View"><a  href="' . site_url() . 'admin/purchase/view_po/' . $r->purchase_order_id  . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
			}

			$payable = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_payable') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-purchase_order_id="' . $r->purchase_order_id   . '"><span class="fa fa-arrow-circle-right"></span></button></span>';

			//For Purchase Expense Module Add Permission
			if (in_array('2915', $role_resources_ids) && $r->status == 'Approved') {
				$expense = '<span data-toggle="tooltip" data-placement="top" title="Purchase Expense"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-purchase_order_id="' . $r->purchase_order_id   . '"><span class="fa fa-dollar"></span></button></span>';
			}

			// //For PO Delete Permission
			// if (in_array('2910', $role_resources_ids)) {
			// 	$combhr = $delete;
			// }

			// //For PO Delete Permission
			// if (in_array('2909', $role_resources_ids)) {
			// 	$combhr = $edit;
			// }

			// //For PO Sent to Supplier Permission
			// if (in_array('29134', $role_resources_ids)) {
			// 	$combhr = $delete;
			// }

			// //For Role Enable and Default to view Permission
			// if (in_array('2907', $role_resources_ids)) {
			// 	$combhr = $view;
			// }

			if ($r->status == 'Approved') {
				$combhr = $edit . $view . $download . $expense .  $delete;
			} else {
				$combhr = $view . $edit . $delete;
			}
			// $combhr = $edit.$delete;
			// $format="PC/PO/".date('Y/m',strtotime($r->created_datetime))."/";

			$data[] = array(
				$i,
				$combhr,
				($r->MRF_no) ?? "-",
				($r->mrf_date) ? (date('d-m-Y', strtotime($r->mrf_date))) : "-",
				$r->porder_id,
				($r->delivery_date) ? date('d-m-Y', strtotime($r->delivery_date)) : '-',

				$r->supplier_name,
				$r->product_names,
				$r->sub_total,
				$r->gst_amount,
				$r->order_total,
				$r->project_code,
				$r->status,

			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $purchase->num_rows(),
			"recordsFiltered" => $purchase->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function p_order_add()
	{
		// echo "<pre>";
		// print_r($this->input->post());
		// exit;
		if ($this->input->post('add_type') == 'purchase_order') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			// echo "<pre>";
			// print_r($_POST);
			// exit;
			/* Server side PHP input validation */
			if ($this->input->post('name_supplier') == 'Select') {
				$Return['error'] = "Please Select a Supplier";
			}

			if ($this->input->post('name_project') == 'Select') {
				$Return['error'] = "Please Select a Project";
			}

			if ($this->input->post('u_type') < 1) {
				$Return['error'] = "Please Add Orderline Items";
			}

			if ($this->input->post('po_dates') == '') {
				$Return['error'] = "Please Select PO Date";
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			$terrrms = $this->db->get('xin_termsandcond')->result();
			$def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result();

			$data = array(
				'po_for' => $this->input->post('name_customer'),
				'project_id' => ($this->input->post('name_project')) ?? '',
				'site_address'	=> ($this->input->post('s_add')=='others')?$this->input->post('other_s_add'):$this->input->post('s_add'),
				'inclusive_gst' => ($this->input->post('inclusive_gst') == "on") ? $this->input->post('inclusive_gst') : "off",
				'gst' => ($this->input->post('order_gst2') == 'Select') ? $def_gst[0]->d_gst : $this->input->post('order_gst2'),
				// 'discount' => ($this->input->post('discount2')) ?? 0,
				// 'discount_value' => (float)$this->input->post('discount2') * (float)$this->input->post('t') / 100,
				'sub_total' => $this->input->post('sub_t'),
				// 'gst_amount' => ($this->input->post('order_gst2') == 'Select') ? $this->input->post('d_gst_i') : $this->input->post('g_val'),
				'gst_amount' => $this->input->post('g_val'),
				'mile_stone' => $this->input->post('milestone_id'),
				'task' => $this->input->post('task_id'),
				'sup_ref' => $this->input->post('sup_ref23'),

				'order_total' => $this->input->post('t'),
				'po_terms' => $this->input->post('term_condition'),
				'sup_bill_id' => $_POST['sup_billing'],
				'supplier_id' => $this->input->post('name_supplier'),
				'payment_term' => $this->input->post('payment_term'),
				'delivery_date' => $this->input->post('delivery_date'),
				'delivery_type' => $this->input->post('delivery_type'),
				'delivery_time' => ($this->input->post('delivery_time') != "" ? $this->input->post('delivery_time') : ''),
				'send_by' => $this->input->post('send_by'),
				'send_date' => $this->input->post('send_date'),
				'status' => 'Draft',
				'note'	=> $this->input->post('important_note'),
				'amendable' => $this->input->post('amendable'),
				'created_by' => $_SESSION['username']['user_id'],
				'created_datetime' => date('Y-m-d h:i:s'),
				'po_dates' => $this->input->post('po_dates')
			);

			// 	echo "<pre>";
			// print_r($data);
			// exit;
			$job1 = $this->Purchase_model->add_order($data);

			if ($this->input->post('name_project') != 'Select') {
				$pr_code = $this->db
					->select('project_code')
					->from('projects')
					->where('project_id', $this->input->post('name_project'))
					->get()
					->row(); // Use row() directly on the result object
				if ($pr_code && isset($pr_code->project_code)) {
					$pr_code = $pr_code->project_code; // Access project_code property
					//Month Sequence PO Start
					$currentMonth = date('y/m', strtotime($this->input->post('po_dates')));

					// Handle sequence
					$this->db->trans_start(); // Start transaction
					$sequence = $this->Purchase_model->get_current_sequence($currentMonth);

					if ($sequence) {
						$new_sequence = $sequence->sequence + 1;
						$this->Purchase_model->update_sequence($currentMonth, $new_sequence);
					} else {
						$new_sequence = 1;
						$this->Purchase_model->insert_sequence($currentMonth, $new_sequence);
					}

					$this->db->trans_complete(); // Complete transaction

					$project_code = $this->Purchase_model->get_project_data($this->input->post('project'));
					$supplier_name = $this->db->select('supplier_name')->from('xin_suppliers')->where('supplier_id', $this->input->post('name_supplier'))->get()->result();
					$supplier_initial = strtoupper(substr($supplier_name[0]->supplier_name, 0, 2));

					$new_porder_id = "OIS/" . $supplier_initial . "/PD/" . $pr_code . "/" . $currentMonth . "/" . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);
					$job_up = $this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $job1]);
				} else {
					//Month Sequence PO Start
					$currentMonth = date('y/m', strtotime($this->input->post('po_dates')));

					// Handle sequence
					$this->db->trans_start(); // Start transaction
					$sequence = $this->Purchase_model->get_current_sequence($currentMonth);

					if ($sequence) {
						$new_sequence = $sequence->sequence + 1;
						$this->Purchase_model->update_sequence($currentMonth, $new_sequence);
					} else {
						$new_sequence = 1;
						$this->Purchase_model->insert_sequence($currentMonth, $new_sequence);
					}

					$this->db->trans_complete(); // Complete transaction

					$project_code = $this->Purchase_model->get_project_data($this->input->post('name_project'));
					$supplier_name = $this->db->select('supplier_name')->from('xin_suppliers')->where('supplier_id', $this->input->post('name_supplier'))->get()->result();
					$supplier_initial = strtoupper(substr($supplier_name[0]->supplier_name, 0, 2));

					$new_porder_id = "OIS/" . $supplier_initial . "/PD/" . $currentMonth . "/" . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);
					$job_up = $this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $job1]);
				}
			} else {
				//Month Sequence PO Start
				$currentMonth = date('y/m', strtotime($this->input->post('po_dates')));

				// Handle sequence
				$this->db->trans_start(); // Start transaction
				$sequence = $this->Purchase_model->get_current_sequence($currentMonth);

				if ($sequence) {
					$new_sequence = $sequence->sequence + 1;
					$this->Purchase_model->update_sequence($currentMonth, $new_sequence);
				} else {
					$new_sequence = 1;
					$this->Purchase_model->insert_sequence($currentMonth, $new_sequence);
				}

				$this->db->trans_complete(); // Complete transaction

				$project_code = $this->Purchase_model->get_project_data($this->input->post('name_project'));
				$supplier_name = $this->db->select('supplier_name')->from('xin_suppliers')->where('supplier_id', $this->input->post('name_supplier'))->get()->result();
				$supplier_initial = strtoupper(substr($supplier_name[0]->supplier_name, 0, 2));

				$new_porder_id = "OIS/" . $supplier_initial . "/PD/" . $currentMonth . "/" . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);
				$job_up = $this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $job1]);
			}
			if ($job_up) {

				// Loop through each table row
				for ($i = 0; $i < count($this->input->post('u_type')); $i++) {
					$u_type = $this->input->post('u_type')[$i];


					// Check if type is 'blank' and skip product fields
					if ($u_type == 'blank') {
						$data_opt = array(
							'porder_id' => $job1,
							'type' => $this->input->post('u_type')[$i],
							'description' => ($this->input->post('u_des')[$i]) ?? '',
							'prd_price' => ($this->input->post('u_price2')[$i]) ?? '',
							'prd_qtn' => ($this->input->post('u_qty')[$i]) ?? '',
							// 'remark' => $this->input->post('u_remark')[$i],
							'unit' => ($this->input->post('u_unit')[$i]) ?? '',
							'prd_total' => ($this->input->post('u_gt')[$i]) ?? '',
							'supplier_id' => $this->input->post('name_supplier'),
							'terms' => $this->input->post('payment_term'),
							'sup_ref' => $this->input->post('sup_ref23'),
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						);
						$this->Purchase_model->add_order_items($data_opt);
					}
					$image_indexs = 0;
					// Handle image rows
					if ($u_type == 'image') {
						if (!empty($_FILES['u_b_img']['tmp_name'][$image_indexs])) {
							$allowed = array('png', 'jpg', 'jpeg', 'pdf', 'gif', 'PNG');
							$allowed_mimes = array('image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'image/gif');

							$profile = "uploads/purchase_order/";
							$set_img = base_url() . "uploads/purchase_order/";

							// Ensure upload directory exists
							if (!is_dir($profile)) {
								mkdir($profile, 0777, true);
							}

							$filename = $_FILES['u_b_img']['name'][$image_indexs];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							$mime_type = mime_content_type($_FILES['u_b_img']['tmp_name'][$image_indexs]);

							// Validate file type by both extension and MIME type
							if (in_array($ext, $allowed) && in_array($mime_type, $allowed_mimes)) {
								$tmp_name = $_FILES["u_b_img"]["tmp_name"][$image_indexs];
								$name = basename(preg_replace("/[^a-zA-Z0-9.]/", "", $filename)); // Sanitize filename
								$newfilename = 'po_u_b_img_' . round(microtime(true)) . '.' . $ext;

								if (move_uploaded_file($tmp_name, $profile . $newfilename)) {
									$fname = $newfilename;
								} else {
									// File upload failed
									$Return['error'] = $this->lang->line('xin_error_uploading_file');
									$this->output($Return);
									exit;
								}
							} else {
								// Invalid file type
								$Return['error'] = $this->lang->line('xin_error_attatchment_type');
								$this->output($Return);
								exit;
							}
						}

						if (!empty($_FILES['u_a_img']['tmp_name'][$image_indexs])) {
							$allowed = array('png', 'jpg', 'jpeg', 'pdf', 'gif', 'PNG');
							$allowed_mimes = array('image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'image/gif');

							$profile = "uploads/purchase_order/";
							$set_img = base_url() . "uploads/purchase_order/";

							// Ensure upload directory exists
							if (!is_dir($profile)) {
								mkdir($profile, 0777, true);
							}

							$filename = $_FILES['u_a_img']['name'][$image_indexs];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							$mime_type = mime_content_type($_FILES['u_a_img']['tmp_name'][$image_indexs]);

							// Validate file type by both extension and MIME type
							if (in_array($ext, $allowed) && in_array($mime_type, $allowed_mimes)) {
								$tmp_name = $_FILES["u_a_img"]["tmp_name"][$image_indexs];
								$name = basename(preg_replace("/[^a-zA-Z0-9.]/", "", $filename)); // Sanitize filename
								$newfilename2 = 'po_u_a_img_' . round(microtime(true)) . '.' . $ext;

								if (move_uploaded_file($tmp_name, $profile . $newfilename2)) {
									$fname2 = $newfilename2;
								} else {
									// File upload failed
									$Return['error'] = $this->lang->line('xin_error_uploading_file');
									$this->output($Return);
									exit;
								}
							} else {
								// Invalid file type
								$Return['error'] = $this->lang->line('xin_error_attatchment_type');
								$this->output($Return);
								exit;
							}
						} else {
							// No file provided for u_a_img
							$fname2 = ''; // Set as empty string to handle optional field
						}


						$data_opt = array(
							'porder_id' => $job1,
							'type' => $this->input->post('u_type')[$i],
							'b_img' => ($fname) ?? '',
							'img_description' => $this->input->post('u_des')[$i],

							'a_img' => ($fname2) ?? '',
							'supplier_id' => $this->input->post('name_supplier'),
							'terms' => $this->input->post('payment_term'),
							'sup_ref' => $this->input->post('sup_ref23'),
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						);

						$this->Purchase_model->add_order_items($data_opt);
						$image_indexs++;
					}

					// Handle product rows
					if ($u_type == 'product' && !empty($this->input->post('product_id')[$i])) {
						$data_opt = array(
							'porder_id' => $job1,
							'prd_id' => $this->input->post('product_id')[$i],

							'supplier_id' => $this->input->post('name_supplier'),
							'prd_price' => $this->input->post('u_price2')[$i],
							'prd_qtn' => $this->input->post('u_qty')[$i],
							'terms' => $this->input->post('payment_term'),
							'sup_ref' => $this->input->post('sup_ref23'),
							// 'remark' => $this->input->post('u_remark')[$i],
							'type' => $this->input->post('u_type')[$i],
							'unit' => $this->input->post('u_unit')[$i],
							'prd_total' => $this->input->post('u_gt')[$i],
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						);
						$this->Purchase_model->add_order_items($data_opt);

						$mapping_data = array(
							'supplier_item_price' => $this->input->post('u_price2')[$i]
						);
						$this->Purchase_model->update_supplier_product_price($mapping_data, $this->input->post('name_supplier'), $this->input->post('product_id')[$i]);
					}
				}

				$Return['result'] = $this->lang->line('xin_success_purchase_order_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;


			// print_r($data);exit;

		}
	}
	public function get_edit_po_details()
	{

		$id = $this->uri->segment('4');
		$data['record'] = $this->Purchase_model->read_get_po($id);
		$data['supplier_products'] = $this->db->select('product.product_id,
													   product.product_name,
													   product.std_uom,
													   xin_supplier_item_mapping.supplier_item_price')
			->from('xin_supplier_item_mapping')
			->join('product', 'xin_supplier_item_mapping.supplier_item_name=product.product_id', 'left')
			->where('supplier_id', $data['record'][0]->supplier_id)
			->get()
			->result();
		$data['products'] = $this->db->get('product')->result();

		// Set the content type to JSON
		$this->output->set_content_type('application/json');

		// Encode the data as JSON and output it
		$this->output->set_output(json_encode($data));
	}
	function get_order_line($sup_id, $po_id)
	{
		$data['record'] = $this->Purchase_model->read_get_po($po_id);
		$data['supplier_products'] = $this->db->select('product.product_id,
													   product.product_name,
													   product.prd_img,
													   product.std_uom,
													   xin_supplier_item_mapping.supplier_item_price')
			->from('xin_supplier_item_mapping')
			->join('product', 'xin_supplier_item_mapping.supplier_item_name=product.product_id', 'left')
			->where('supplier_id', $sup_id)
			->get()
			->result();
		$data['products'] = $this->db->get('product')->result();

		// Set the content type to JSON
		$this->output->set_content_type('application/json');

		// Encode the data as JSON and output it
		$this->output->set_output(json_encode($data));
	}
	public function read_order()
	{
		// echo "fsdfds";exit;
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('purchase_order_id');
		// $result = $this->Purchase_model->read_purchase_order($id);
		$result = $this->Purchase_model->read_get_po($id);
		// $format="PTS/PO/".date('Y/m',strtotime($result[0]->created_datetime))."/";
		$terrrms = $this->db->get('xin_termsandcond')->result();
		// print_r($result);exit();
		$data = array(
			// 'purchase_req_id' => $result[0]->purchase_requistion_id,
			'porder_id' => $result[0]->porder_id,
			'cust_name' => $result[0]->first_name . " " . $result[0]->last_name,
			'project_name' => $result[0]->project_title,
			'cust_id' => $result[0]->po_for,
			// 'prd_uom_from_prq' => $result[0]->prd_uom_from_prq,
			'site_add' => $result[0]->site_add,
			'project' => $result[0]->project_id,
			'gst' => $result[0]->gst,
			// 'gst' => $result[0]->gst,
			'inclusive_gst' => $result[0]->inclusive_gst,
			'discount1' => $result[0]->discount,
			// 'term' => ($result[0]->po_terms == '') ? $terrrms[0]->po_term : $result[0]->po_terms,
			'term' => $result[0]->payment_term,
			'supplier_id' => $result[0]->supplier_id,
			'supplier' => $result[0]->supplier_name,
			'sup_bill_id' => $result[0]->sup_bill_id,
			'sup_ref' => $result[0]->sup_ref,
			'milestone' => $result[0]->mile_stone,
			'description_name' => $result[0]->task,
			'pur_req_id' => $result[0]->preq_id,
			'status' => $result[0]->status,
			'status_reason' => $result[0]->status_reason,
			'delivery_date' => $result[0]->delivery_date,
			'podates' => $result[0]->po_dates,
			'delivery_type' => $result[0]->delivery_type,
			'delivery_time' => $result[0]->delivery_time,
			'note' => $result[0]->note,
			'amendable' => $result[0]->amendable,
			'send_by' => $result[0]->send_by,
			'send_date' => $result[0]->send_date,
			"all_items" => $this->db->get('product')->result(),
			'payment_term' => $result[0]->payment_term,
			// 'products' => $this->db->select('purchase_order.*,
			// 	product.product_name,
			// 	purchase_order_item_mapping.supplier_id,
			// 	purchase_order_item_mapping.prd_price,
			// 	purchase_order_item_mapping.prd_qtn,
			// 	purchase_order_item_mapping.prd_total,
			// 	purchase_order_item_mapping.sup_ref,
			// 	purchase_order_item_mapping.terms,
			// 	purchase_order_item_mapping.description,
			// 	purchase_order_item_mapping.unit,

			// 	purchase_order_item_mapping.prd_id,
			// 	purchase_order_item_mapping.remark
			// ')
			// 	->from('purchase_order')
			// 	->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id')
			// 	->join('product', 'purchase_order_item_mapping.prd_id=product.product_id')
			// 	->where('purchase_order.purchase_order_id', $id)
			// 	->get()
			// 	->result(),

			'settings' => $this->Xin_model->read_company_setting_info(1),
			'invoice_settings' => $this->Xin_model->read_setting_info(1),
			'get_all_images' => $this->Purchase_model->get_all_images($id)
		);

		// print_r($data['sup_bill_id']);exit;

		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/purchase/dialog_purchase_order', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update_order()
	{

		// print_r($this->input->post('order_gst3'));
		// exit;
		if ($this->input->post('edit_type') == 'edit_purchase_order') {
			// $id = $this->uri->segment(4);
			$image_indexs = 0;

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('po_dates1')  == '') {
				$Return['error'] = "Please Select PO Date";
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			/* Server side PHP input validation */
			// if($this->input->post('u_project_id')==='') {
			// 	$Return['error'] = $this->lang->line('error_project_field');
			// } else 
			$id = $this->input->post('purchase_order_id');
			// $data = array(
			// 	'preq_id'=>$this->input->post('purchase_req_id'),
			// 	'porder_id'=>$this->input->post('porder_id'),
			// 	'project_id'=>$this->input->post('project'),
			// 	'status'=>"Approved",				
			// 	'created_by' => $_SESSION['username']['user_id'],
			// 	'created_datetime' => date('Y-m-d h:i:s'),

			// );
			// $result = $this->Purchase_model->update_order($data, $id);
			// $result=$this->Purchase_model->add_order($data);
			$terrrms = $this->db->get('xin_termsandcond')->result();
			$def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result();
			$result = $this->db->update('purchase_order', [
				'po_for' => $this->input->post('emp'),
				'project_id' => $this->input->post('project'),
				'site_add'	=> ($this->input->post('s_add1')=='others')?$this->input->post('other_s_add1'):$this->input->post('s_add1'),

				// 'gst' => $this->input->post('order_gst'),
				'discount' => $this->input->post('discount1'),
				'sup_bill_id' => $this->input->post('supplier_billing1'),
				'mile_stone'	=> $this->input->post('milestone_id1'),
				'task'	=> $this->input->post('task_id1'),
				'delivery_date' => $this->input->post('d_delivery_date'),
				'delivery_type' => $this->input->post('u_delivery_type'),
				'delivery_time' => $this->input->post('u_delivery_time'),
				'send_by' => $this->input->post('u_send_by'),
				'send_date' => $this->input->post('u_send_date'),
				'inclusive_gst' => ($this->input->post('inclusive_gst2') == "on") ? $this->input->post('inclusive_gst2') : "off",
				'gst' => ($this->input->post('order_gst3') == '') ? $def_gst[0]->d_gst : $this->input->post('order_gst3'),
				'sub_total' => $this->input->post('sub_t1'),
				'note' => $this->input->post('u_note'),
				'amendable' => $this->input->post('amendable1'),

				// 'gst_amount' => ($this->input->post('order_gst3') == 'Select') ?
				// 	$def_gst[0]->d_gst * $this->input->post('sub_t1') / 100
				// 	:
				// 	$this->input->post('order_gst3') * $this->input->post('sub_t1') / 100,
				'gst_amount' => ($this->input->post('order_gst3') == $def_gst[0]->d_gst) ? $this->input->post('d_gst_i1') : $this->input->post('g_val1'),

				'order_total' => $this->input->post('t1'),
				// 'status' => $this->input->post('status'),
				'status_reason' => $this->input->post('status_reason'),
				'payment_term' => $this->input->post('payment_term2'),
				'po_dates' => $this->input->post('po_dates1'),
				'po_terms' => ($this->input->post('editor1') == '') ? $terrrms[0]->po_term : $this->input->post('editor1'),
			], ['purchase_order_id' => $id]);

			if ($this->input->post('old_supplier_id') != $this->input->post('name_supplier1')) {
				// Get the new supplier initials
				$supplier_name = $this->db->select('supplier_name')->from('xin_suppliers')->where('supplier_id', $this->input->post('name_supplier1'))->get()->result();
				$supplier_initial = strtoupper(substr($supplier_name[0]->supplier_name, 0, 2));

				// Fetch the existing porder_id
				$existing_order = $this->db->select('porder_id')->from('purchase_order')->where('purchase_order_id', $id)->get()->row();

				if ($existing_order) {
					$existing_porder_id = $existing_order->porder_id;

					// Explode the porder_id by '/' to replace only the initials part
					$parts = explode('/', $existing_porder_id);

					// Assuming format: OIS/{initial}/PD/{month}/{sequence}
					// Replace the initial part only (index 1)
					$parts[1] = $supplier_initial;

					// Reconstruct the porder_id
					$new_porder_id = implode('/', $parts);

					// Update the purchase order
					$job_up = $this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $id]);
				}
			}

			if ($this->input->post('old_project') != $this->input->post('project')) {
				// Fetch the new project code
				$project_data = $this->db
					->select('project_code')
					->from('projects')
					->where('project_id', $this->input->post('project'))
					->get()
					->row();



				// If project code exists
				if (!empty($project_data->project_code)) {
					$new_project_code = $project_data->project_code;

					// Get the existing porder_id
					$existing_order = $this->db
						->select('porder_id')
						->from('purchase_order')
						->where('purchase_order_id', $id)
						->get()
						->row();

					if ($existing_order && !empty($existing_order->porder_id)) {
						$existing_porder_id = $existing_order->porder_id;
						$parts = explode('/', $existing_porder_id);

						// If format already has project code (6 parts), replace it
						if (count($parts) == 7) {
							$parts[3] = $new_project_code;
						}
						// If format does not have project code (5 parts), insert the new project code
						// elseif (count($parts) == 5) {
						// 	array_splice($parts, 3, 0);
						// }

						$new_porder_id = implode('/', $parts);

						// Update in database
						$this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $id]);
					}
				} else {
					// Fetch the existing porder_id
					$existing_order = $this->db->select('porder_id')->from('purchase_order')->where('purchase_order_id', $id)->get()->row();
					$parts = explode('/', $existing_order->porder_id);
					// print_r($parts);
					if ($existing_order) {
						if (count($parts) == 6) {
							array_splice($parts, 3, 0);
						}

						$new_porder_id = implode('/', $parts);
						// print_r(array_splice($parts, 3, 0));
						// exit;
						// Update in database
						$this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $id]);
					}
				}
			}


			if ($this->input->post('old_po_dates1') != $this->input->post('po_dates1')) {
				$currentMonthYear = date('ym', strtotime($this->input->post('po_dates1')));
				$year = date('y', strtotime($this->input->post('po_dates1')));
				$month = date('m', strtotime($this->input->post('po_dates1')));
				$project_id = $this->input->post('project');
				$supplier_id = $this->input->post('name_supplier1');

				// Fetch supplier initials
				$supplier_data = $this->db->select('supplier_name')->from('xin_suppliers')->where('supplier_id', $supplier_id)->get()->row();
				$supplier_initial = strtoupper(substr($supplier_data->supplier_name, 0, 2));

				// Fetch project code
				$project_data = $this->db->select('project_code')->from('projects')->where('project_id', $project_id)->get()->row();
				$project_code = $project_data ? $project_data->project_code : 'NA';

				// Handle sequence
				$this->db->trans_start();
				$sequence_data = $this->Purchase_model->get_current_sequence($currentMonthYear);
				$new_sequence = $sequence_data ? $sequence_data->sequence + 1 : 1;

				if ($sequence_data) {
					$this->Purchase_model->update_sequence($currentMonthYear, $new_sequence);
				} else {
					$this->Purchase_model->insert_sequence($currentMonthYear, $new_sequence);
				}
				$this->db->trans_complete();

				// Rebuild porder ID in the desired format:
				$new_porder_id = "OIS/$supplier_initial/PD/$project_code/$year/$month/" . sprintf('%04d', $new_sequence);

				// Update the porder ID in the database
				$this->db->update('purchase_order', ['porder_id' => $new_porder_id], ['purchase_order_id' => $id]);
			}



			if ($result) {
				$this->db->delete('purchase_order_item_mapping', ['porder_id' => $id]);

				// Loop through each table row
				for ($i = 0; $i < count($this->input->post('e_u_type')); $i++) {
					$u_type = $this->input->post('e_u_type')[$i];
					$image_status = $this->input->post('image_status')[$i];

					// Check if type is 'blank' and skip product fields
					if ($u_type == 'blank') {
						$data_opt = array(
							'porder_id' => $id,
							'type' => $this->input->post('e_u_type')[$i],
							'description' => ($this->input->post('u_item1')[$i]) ?? '',
							'prd_price' => $this->input->post('rate1')[$i],
							'prd_qtn' => $this->input->post('quantity1')[$i],
							// 'remark' => $this->input->post('u_remark')[$i],
							'unit' => ($this->input->post('unit1')[$i]) ?? '',
							'supplier_id' => $this->input->post('name_supplier1'),
							'terms' => $this->input->post('payment_term2'),
							'sup_ref' => $this->input->post('sup_ref4'),
							'prd_total' => $this->input->post('t1')[$i],
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						);
						$this->Purchase_model->add_order_items($data_opt);
					}

					// Handle image rows
					if ($u_type == 'image') {
						// print_r($this->input->post('old_b_img'));
						// exit;
						$fname = "";
						$fname2 = "";
						if (!empty($_FILES['e_u_b_img']['tmp_name'][$i]) && $image_status == 0) {

							$allowed = array('png', 'jpg', 'jpeg', 'pdf', 'gif');
							$allowed_mimes = array('image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'image/gif');

							$profile = "uploads/purchase_order/";
							$set_img = base_url() . "uploads/purchase_order/";

							// Ensure upload directory exists
							if (!is_dir($profile)) {
								mkdir($profile, 0777, true);
							}

							$filename = $_FILES['e_u_b_img']['name'][$i];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							$mime_type = mime_content_type($_FILES['e_u_b_img']['tmp_name'][$i]);

							// Validate file type by both extension and MIME type
							if (in_array($ext, $allowed) && in_array($mime_type, $allowed_mimes)) {
								$tmp_name = $_FILES["e_u_b_img"]["tmp_name"][$i];
								$name = basename(preg_replace("/[^a-zA-Z0-9.]/", "", $filename)); // Sanitize filename
								$newfilename = 'po_u_b_img_' . round(microtime(true)) . '.' . $ext;

								if (move_uploaded_file($tmp_name, $profile . $newfilename)) {
									$fname = $newfilename;
								} else {
									// File upload failed
									$Return['error'] = $this->lang->line('xin_error_uploading_file');
									$this->output($Return);
									exit;
								}
							} else {
								// Invalid file type
								$Return['error'] = $this->lang->line('xin_error_attatchment_type');
								$this->output($Return);
								exit;
							}
						} else {
							$fname = $this->input->post('old_b_img')[$image_indexs];
						}
						if (!empty($_FILES['e_u_a_img']['tmp_name'][$i]) && $image_status == 0) {
							$allowed = array('png', 'jpg', 'jpeg', 'pdf', 'gif');
							$allowed_mimes = array('image/png', 'image/jpeg', 'image/jpg', 'application/pdf', 'image/gif');

							$profile = "uploads/purchase_order/";
							$set_img = base_url() . "uploads/purchase_order/";

							// Ensure upload directory exists
							if (!is_dir($profile)) {
								mkdir($profile, 0777, true);
							}

							$filename = $_FILES['e_u_a_img']['name'][$i];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);
							$mime_type = mime_content_type($_FILES['e_u_a_img']['tmp_name'][$i]);

							// Validate file type by both extension and MIME type
							if (in_array($ext, $allowed) && in_array($mime_type, $allowed_mimes)) {
								$tmp_name = $_FILES["e_u_a_img"]["tmp_name"][$i];
								$name = basename(preg_replace("/[^a-zA-Z0-9.]/", "", $filename)); // Sanitize filename
								$newfilename2 = 'po_u_a_img_' . round(microtime(true)) . '.' . $ext;

								if (move_uploaded_file($tmp_name, $profile . $newfilename2)) {
									$fname2 = $newfilename2;
								} else {
									// File upload failed
									$Return['error'] = $this->lang->line('xin_error_uploading_file');
									$this->output($Return);
									exit;
								}
							} else {
								// Invalid file type
								$Return['error'] = $this->lang->line('xin_error_attatchment_type');
								$this->output($Return);
								exit;
							}
						} else {
							// If no new file uploaded, fallback to old image
							$fname2 = ($this->input->post('old_a_img')[$i]) ?? '';
						}


						$data_opt = array(
							'porder_id' => $id,
							'type' => $this->input->post('e_u_type')[$i],
							'b_img' => $fname,
							'a_img' => $fname2,

							'img_description' => $this->input->post('u_img_description')[$i],
							'supplier_id' => $this->input->post('name_supplier1'),
							'terms' => $this->input->post('payment_term2'),
							'sup_ref' => $this->input->post('sup_ref4'),
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						);

						$this->Purchase_model->add_order_items($data_opt);
						$image_indexs++;
					}

					// Handle product rows
					if ($u_type == 'product' && !empty($this->input->post('u_item1')[$i])) {
						$data_opt = array(
							'porder_id' => $id,
							'prd_id' => $this->input->post('u_item1')[$i],
							// 'prd_color' => $this->input->post('product_color1')[$i],
							// 'prd_color_name' => $this->input->post('u_color1')[$i],
							'supplier_id' => $this->input->post('name_supplier1'),
							'prd_price' => $this->input->post('rate1')[$i],
							'prd_qtn' => $this->input->post('quantity1')[$i],
							'terms' => $this->input->post('payment_term2'),
							'type' => $this->input->post('e_u_type')[$i],
							'unit' => $this->input->post('unit1')[$i],
							'sup_ref' => $this->input->post('sup_ref4'),
							'prd_total' => $this->input->post('t1')[$i],
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						);
						$this->Purchase_model->add_order_items($data_opt);

						$mapping_data = array(
							'supplier_item_price' => $this->input->post('rate1')[$i]
						);
						$this->Purchase_model->update_supplier_product_price($mapping_data, $this->input->post('name_supplier1'), $this->input->post('u_item1')[$i]);
					}
				}

				// print_r($_POST);exit();
				$Return['result'] = "Purchase Order Updated";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);

			exit;
		}
	}
	public function delete_order()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$img_data = $this->db->where('porder_id', $id)->get('purchase_order_item_mapping')->result();
		foreach ($img_data as $item) {
			// Check if b_img exists and delete it
			if (!empty($item->b_img)) {
				$b_img_path = FCPATH . 'uploads/purchase_order/' . $item->b_img;
				if (file_exists($b_img_path)) {
					unlink($b_img_path);
				}
			}

			// Check if a_img exists and delete it
			if (!empty($item->a_img)) {
				$a_img_path = FCPATH . 'uploads/purchase_order/' . $item->a_img;
				if (file_exists($a_img_path)) {
					unlink($a_img_path);
				}
			}
		}
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Purchase_model->delete_order_record($id);
		$this->Purchase_model->delete_order_item_record($id);

		if (isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_purchase_order_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

	public function view_po()
	{
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);
		$result = $this->Purchase_model->read_get_po1($id);
		// print_r($result);exit;
		$res = $this->db->select('first_name,last_name,signature,contact_no')->from('xin_employees')->where('user_id', $result[0]->po_for)->get()->result();
		$abc = $this->db->select('site_address, location')->from('purchase_requistion')->where('purchase_requistion_id', $result[0]->preq_id)->get()->result();
		$def = $this->db->select('site_add')->from('purchase_order')->where('purchase_order_id', $id)->get()->result();

		// $res = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->created_by)->get()->result();
		// $res1 = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->approved_by)->get()->result();
		// $res2 = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->po_for)->get()->result();



		// if (!empty($result[0]->approved_by)) {
		// 	$res = $this->db->select('first_name,last_name,signature')->from('xin_employees')->where('user_id', $result[0]->approved_by)->get()->result();
		// }
		$po_name = ($result[0]->po_for != 0) ? $res[0]->first_name . " " . $res[0]->last_name : '';
		$data = array(
			'title' => $this->Xin_model->site_title(),
			'breadcrumbs' => 'Purchase Order View',
			'path_url' => 'purchase_order',

			'porder_id' => $result[0]->porder_id,
			'project_title' => $result[0]->project_title,
			'supplier_name' => $result[0]->supplier_name,
			'supplier_address' => $result[0]->supplier_address,
			'email_address' => $result[0]->email_address,
			'gst_amount' => $result[0]->gst_amount,

			'milestone' => $result[0]->mile_stone,
			'description_name' => $result[0]->description,

			'contact_person' => $result[0]->supplier_contact_person,
			// 'supplier_pincode' => $result[0]->supplier_pincode,
			'supplier_phone' => $result[0]->supplier_phone,
			'amd_line' => $result[0]->amd_line,
			'cantactperson' => $result[0]->cantactperson,
			'po_for' => $po_name,
			'prep_phone' => ($result[0]->approved_by) ?  ($res[0]->contact_no) ?? "" : '',
			'status' =>	$result[0]->status,
			'delivery_date' => $result[0]->delivery_date,
			'delivery_type' => $result[0]->delivery_type,
			'delivery_time' => $result[0]->delivery_time,
			'note' => $result[0]->note,
			'amendable' => $result[0]->amendable,
			'site_add' => $result[0]->site_add,
			'gst1' =>	$result[0]->gst,
			'inclusive_gst' =>	$result[0]->inclusive_gst,
			'delivery' => ($abc[0]->site_address) ?? '',
			'delivery1' => $def[0]->site_add,
			'location' => ($abc[0]->location) ?? '',
			'preq_id' => ($abc[0]->preq_id) ?? '',
			// 'app_by' => ($result[0]->approved_by) ? $res[0]->first_name . " " . $res[0]->last_name : " ",
			'signature' => ($result[0]->approved_by) ? $res[0]->signature : '',
			'payment_term' => ($result[0]->terms) ?? "",
			'sup_ref' => ($result[0]->sup_ref) ?? "",
			'term' => $result[0]->po_terms,
			'contact_no' => ($res[0]->contact_no) ?? '',
			'first_name' => ($res[0]->first_name) ?? '',
			'last_name' => ($res[0]->last_name) ?? '',
			'send_by' => $result[0]->send_by,
			'send_date' => $result[0]->send_date,
			// 'email_address' => $result[0]->email_address,
			// 'contact_person' => $result[0]->supplier_contact_person,

			'created_date' => date('d-m-Y', strtotime($result[0]->created_datetime)),
			'get_purchse_items' => $this->db->select('purchase_order.*,
													  product.product_name as description, 
													  product.prd_img as image, 
													  product.std_uom,																							
													  purchase_order_item_mapping.description as blank,
													purchase_order_item_mapping.prd_uom_from_prq, 

													  purchase_order_item_mapping.b_img,
													  purchase_order_item_mapping.a_img,
													  purchase_order_item_mapping.img_description,
													  purchase_order_item_mapping.prd_qtn,
													  purchase_order_item_mapping.unit,
													  purchase_order_item_mapping.prd_price,
													  purchase_order_item_mapping.prd_total,
													  purchase_order_item_mapping.prd_total,
													  purchase_order_item_mapping.remark,
													  purchase_order_item_mapping.sup_ref,
													  purchase_order_item_mapping.prd_color,
													  purchase_order_item_mapping.prd_color_name,
													  purchase_order_item_mapping.type,
													  subquery.total_prd_total')
				->from('purchase_order')
				->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id')
				->join('product', 'purchase_order_item_mapping.prd_id=product.product_id', 'left')
				// ->join('conf_purchase_req','purchase_order.preq_id=conf_purchase_req.conf_pr_id')
				->join("(SELECT porder_id, SUM(prd_total) as total_prd_total 
						FROM purchase_order_item_mapping 
						GROUP BY porder_id) as subquery", 'purchase_order.purchase_order_id = subquery.porder_id', 'left')
				->where('purchase_order.purchase_order_id', $id)
				->get()
				->result(),
			// 'customer_name' => $res[0]->first_name . " " . $res[0]->last_name,
			// 'customer_phone' => $result[0]->contact_no,
			// 'created_date' => date('d-m-Y', strtotime($result[0]->podate)),
			'settings' => $this->Xin_model->read_company_setting_info(1),
			'invoice_settings' => $this->Xin_model->read_setting_info(1),
			// 'get_purchse_items' => $this->Purchase_model->get_all_items($id),
			// 'get_images' => $this->Purchase_model->get_all_images($id),
			'company_info' => $this->Xin_model->read_company_setting_info(1)
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$data['subview'] = $this->load->view('admin/purchase/purchase_order_view', $data, TRUE);
			$this->load->view('admin/layout/pms/layout_pms', $data); //page load
		} else {
			redirect('admin/');
		}
	}
	public function get_project_details($id)
	{
		$result = $this->db->where(['project_id' => $id])
			->get('projects')->result();
		echo json_encode($result);
	}
	public function pdf_create()
	{
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);

		$result = $this->Purchase_model->read_get_po1($id);
		$res = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->approved_by)->get()->result();
		// Read the image file into a variable and encode it as base64
		$image_path = base_url() . 'uploads/logo/PCEC.JPG'; // Replace with the actual image path
		$image_pathF = base_url() . 'uploads/logo/Footer.JPG'; // Replace with the actual image path
		// Disable SSL verification
		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
			],
		]);
		$image_data = file_get_contents($image_path, false, $context);
		$image_base64 = base64_encode($image_data);
		$image_dataF = file_get_contents($image_pathF, false, $context);
		$image_base64F = base64_encode($image_dataF);

		// $query = $this->db->get('xin_quo')->result();

		// // Access the 'logo1' property from the query result
		// $logoUrl = base_url('uploads/quo/' . $query[0]->logo1);
		// $logoUrl2 = base_url('uploads/quo/' . $query[0]->logo2);
		// $logoUrl3 = base_url('uploads/quo/' . $query[0]->logo3);
		// $logoUrl4 = base_url('uploads/quo/' . $query[0]->logo4);
		// // $signature = base_url('uploads/document/signature/' . $res[0]->signature);

		// $image_data = file_get_contents($logoUrl, false, $context);
		// $image_data2 = file_get_contents($logoUrl2, false, $context);
		// $image_data3 = file_get_contents($logoUrl3, false, $context);
		// $image_data4 = file_get_contents($logoUrl4, false, $context);
		// // $image_data5 = file_get_contents($signature, false, $context);

		// $logoUrl = base64_encode($image_data);
		// $logoUrl2 = base64_encode($image_data2);
		// $logoUrl3 = base64_encode($image_data3);
		// $logoUrl4 = base64_encode($image_data4);
		// // $signature = base64_encode($image_data5);

		$data = array(
			'title' => $this->Xin_model->site_title(),
			'breadcrumbs' => 'Purchase Order View',
			'path_url' => 'purchase_order',
			'address' => $result[0]->site_add,
			'porder_id' => $result[0]->porder_id,
			'project_title' => $result[0]->project_title,
			'supplier_name' => $result[0]->supplier_name,
			'supplier_address' => $result[0]->supplier_address,
			'supplier_pincode' => $result[0]->supplier_pincode,
			'supplier_phone' => $result[0]->supplier_phone,
			'status' =>	$result[0]->status,
			'gst1' =>	$result[0]->gst,
			'app_by' => ($result[0]->approved_by) ? $res[0]->first_name . " " . $res[0]->last_name : " ",
			'term' => $result[0]->po_terms,

			'get_all_item' => $this->db->select('purchase_order.*,
												product.product_name, 
												product.description,
												product.std_uom,	
		purchase_order_item_mapping.prd_qtn,
		purchase_order_item_mapping.prd_price,
		purchase_order_item_mapping.prd_total,
		subquery.total_prd_total')
				->from('purchase_order')
				->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id')
				->join('product', 'purchase_order_item_mapping.prd_id=product.product_id')
				// ->join('conf_purchase_req','purchase_order.preq_id=conf_purchase_req.conf_pr_id')
				->join("(SELECT porder_id, SUM(prd_total) as total_prd_total 
		FROM purchase_order_item_mapping 
		GROUP BY porder_id) as subquery", 'purchase_order.purchase_order_id = subquery.porder_id', 'left')
				->where('purchase_order.purchase_order_id', $id)
				->get()
				->result(),
			'customer_name' => $res[0]->first_name . " " . $res[0]->last_name,
			'customer_phone' => $res[0]->phone,
			'created_date' => date('d-m-Y', strtotime($result[0]->podate)),
			'settings' => $this->Xin_model->read_company_setting_info(1),
			'invoice_settings' => $this->Xin_model->read_setting_info(1),
			'image_base64' => $image_base64,
			'image_base64F' => $image_base64F,
		);




		$html = $this->load->view('admin/purchase/get_purchase_receipt', $data, true);

		// Create Dompdf instance with options
		$options = new Options();
		$options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
		// $options->set('margin_top', '10mm'); // Set top margin
		// $options->set('margin_right', '10mm'); // Set right margin
		// $options->set('margin_bottom', '10mm'); // Set bottom margin
		// $options->set('margin_left', '10mm'); // Set left margin
		$dompdf = new Dompdf($options);

		// Load HTML content
		$dompdf->loadHtml($html);

		// (Optional) Set paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render PDF (output)
		$dompdf->render();

		// Output the PDF to the browser
		$dompdf->stream($result[0]->porder_id . ".pdf");
	}
	public function new_pdf_create()
	{
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);
		$result = $this->Purchase_model->read_get_po1($id);
		// print_r($result);exit;
		$res = $this->db->select('first_name,last_name,signature,contact_no')->from('xin_employees')->where('user_id', $result[0]->approved_by)->get()->result();
		$abc = $this->db->select('site_address, location')->from('purchase_requistion')->where('purchase_requistion_id', $result[0]->preq_id)->get()->result();
		$def = $this->db->select('site_add')->from('purchase_order')->where('purchase_order_id', $id)->get()->result();

		// $res = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->created_by)->get()->result();
		// $res1 = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->approved_by)->get()->result();
		// $res2 = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->po_for)->get()->result();
		$inv = $this->db->get('xin_system_setting')->result();
		$image_path = base_url() . 'uploads/logo/logo_120_final.png'; // Replace with the actual image path

		// Disable SSL verification
		$context = stream_context_create([
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
			],
		]);
		$image_data = file_get_contents($image_path, false, $context);
		$image_base64 = base64_encode($image_data);
		$query = $this->db->get('xin_quo')->result();

		// Access the 'logo1' property from the query result
		$logoUrl = base_url('uploads/quo/' . $query[0]->logo1);
		$logoUrl2 = base_url('uploads/quo/' . $query[0]->logo2);
		$logoUrl3 = base_url('uploads/quo/' . $query[0]->logo3);
		$logoUrl4 = base_url('uploads/quo/' . $query[0]->logo4);


		$image_data = file_get_contents($logoUrl, false, $context);
		$image_data2 = file_get_contents($logoUrl2, false, $context);
		$image_data3 = file_get_contents($logoUrl3, false, $context);
		$image_data4 = file_get_contents($logoUrl4, false, $context);

		$defaultLogo = base64_encode(file_get_contents('uploads/product/default.jpg'));

		$logoUrl = base64_encode($image_data);
		$logoUrl2 = base64_encode($image_data2);
		$logoUrl3 = base64_encode($image_data3);
		$logoUrl4 = base64_encode($image_data4);


		// $res = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->created_by)->get()->result();
		// $res1 = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->approved_by)->get()->result();
		// $res2 = $this->db->select('first_name,last_name')->from('xin_employees')->where('user_id', $result[0]->po_for)->get()->result();



		// if (!empty($result[0]->approved_by)) {
		// 	$res = $this->db->select('first_name,last_name,signature')->from('xin_employees')->where('user_id', $result[0]->approved_by)->get()->result();
		// }
		$data = array(
			'title' => $this->Xin_model->site_title(),
			'breadcrumbs' => 'Purchase Order View',
			'path_url' => 'purchase_order',
			'logo' => $image_base64,
			'porder_id' => $result[0]->porder_id,
			'project_title' => $result[0]->project_title,
			'supplier_name' => $result[0]->supplier_name,
			'supplier_address' => $result[0]->supplier_address,
			'email_address' => $result[0]->email_address,

			'milestone' => $result[0]->mile_stone,
			'gst_amount' => $result[0]->gst_amount,
			'description_name' => $result[0]->description,
			'amd_line' => $result[0]->amd_line,
			'cantactperson' => $result[0]->cantactperson,
			'contact_person' => $result[0]->supplier_contact_person,
			// 'supplier_pincode' => $result[0]->supplier_pincode,
			'supplier_phone' => $result[0]->supplier_phone,
			'po_for' => ($result[0]->approved_by) ?  $res[0]->first_name . " " . $res[0]->last_name : '',
			'prep_phone' => ($result[0]->approved_by) ?  ($res[0]->contact_no) ?? "" : '',
			'status' =>	$result[0]->status,
			'delivery_date' => $result[0]->delivery_date,
			'delivery_type' => $result[0]->delivery_type,
			'delivery_time' => $result[0]->delivery_time,
			'note' => $result[0]->note,
			'amendable' => $result[0]->amendable,
			'site_add' => $result[0]->site_add,
			'gst1' =>	$result[0]->gst,
			'inclusive_gst' =>	$result[0]->inclusive_gst,
			'delivery' => ($abc[0]->site_address) ?? '',
			'delivery1' => $def[0]->site_add,
			'location' => ($abc[0]->location) ?? '',
			'preq_id' => ($abc[0]->preq_id) ?? '',
			'app_by' => ($result[0]->approved_by) ? $res[0]->first_name . " " . $res[0]->last_name : " ",
			'signature' => ($result[0]->approved_by) ? $res[0]->signature : '',
			'payment_term' => ($result[0]->terms) ?? "",
			'sup_ref' => ($result[0]->sup_ref) ?? "",
			'term' => $result[0]->po_terms,
			'contact_no' => ($res[0]->contact_no) ?? '',
			'first_name' => ($res[0]->first_name) ?? '',
			'last_name' => ($res[0]->last_name) ?? '',
			'send_by' => $result[0]->send_by,
			'send_date' => $result[0]->send_date,
			// 'email_address' => $result[0]->email_address,
			// 'contact_person' => $result[0]->supplier_contact_person,
			'created_date' => date('d-m-Y', strtotime($result[0]->created_datetime)),
			'get_purchse_items' => $this->db->select('purchase_order.*,
													  product.product_name as description, 
													  product.prd_img as image, 
													  product.std_uom,																							
													  purchase_order_item_mapping.description as blank,
													  purchase_order_item_mapping.b_img,
													  purchase_order_item_mapping.a_img,
													  purchase_order_item_mapping.img_description,
													  purchase_order_item_mapping.prd_qtn,
													  purchase_order_item_mapping.unit,
													  purchase_order_item_mapping.prd_price,
													  purchase_order_item_mapping.prd_total,
													  purchase_order_item_mapping.prd_total,
													  purchase_order_item_mapping.remark,
													  purchase_order_item_mapping.prd_color,
													  purchase_order_item_mapping.prd_color_name,
													  purchase_order_item_mapping.type,
													  subquery.total_prd_total')
				->from('purchase_order')
				->join('purchase_order_item_mapping', 'purchase_order.purchase_order_id=purchase_order_item_mapping.porder_id')
				->join('product', 'purchase_order_item_mapping.prd_id=product.product_id', 'left')
				// ->join('conf_purchase_req','purchase_order.preq_id=conf_purchase_req.conf_pr_id')
				->join("(SELECT porder_id, SUM(prd_total) as total_prd_total 
						FROM purchase_order_item_mapping 
						GROUP BY porder_id) as subquery", 'purchase_order.purchase_order_id = subquery.porder_id', 'left')
				->where('purchase_order.purchase_order_id', $id)
				->get()
				->result(),
			// 'customer_name' => $res[0]->first_name . " " . $res[0]->last_name,
			// 'customer_phone' => $result[0]->contact_no,
			// 'created_date' => date('d-m-Y', strtotime($result[0]->podate)),
			'settings' => $this->Xin_model->read_company_setting_info(1),
			'invoice_settings' => $this->Xin_model->read_setting_info(1),
			// 'get_purchse_items' => $this->Purchase_model->get_all_items($id),
			// 'get_images' => $this->Purchase_model->get_all_images($id),
			'company_info' => $this->Xin_model->read_company_setting_info(1),

			'logoUrl'  => !empty($logoUrl) ? $logoUrl : $defaultLogo,
			'logoUrl2' => !empty($logoUrl2) ? $logoUrl2 : $defaultLogo,
			'logoUrl3' => !empty($logoUrl3) ? $logoUrl3 : $defaultLogo,
			'logoUrl4' => !empty($logoUrl4) ? $logoUrl4 : $defaultLogo,

		);
		// print_r($data);exit;
		$html = $this->load->view('admin/purchase/get_po_receipt', $data, true);

		// Create Dompdf instance with options
		$options = new Options();
		$options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
		// $options->set('margin_top', '10mm'); // Set top margin
		// $options->set('margin_right', '10mm'); // Set right margin
		// $options->set('margin_bottom', '10mm'); // Set bottom margin
		// $options->set('margin_left', '10mm'); // Set left margin
		$dompdf = new Dompdf($options);

		// Load HTML content
		$dompdf->loadHtml($html);

		// (Optional) Set paper size and orientation
		$dompdf->setPaper('A4', 'portrait');

		// Render PDF (output)
		$dompdf->render();

		// Output the PDF to the browser
		$dompdf->stream($result[0]->porder_id . ".pdf", ["Attachment" => false]);


		// $session = $this->session->userdata('username');
		// if (!empty($session)) {

		// 	$this->load->view('admin/purchase/get_po_receipt', $data); //page load
		// } else {
		// 	redirect('admin/');
		// }
	}
	public function get_details()
	{
		$id = $this->uri->segment(4);
		$u_data = array(
			'modified_by' => $_SESSION['username']['user_id'],
			'modified_datetime' => date('Y-m-d'),
			'status' => 'Approved'
		);
		$rest = $this->Purchase_model->update($u_data, $id);

		if ($rest) {
			$Return['result'] = "Purchase Requsition Confirmed";
		} else {
			$Return['error'] = "Confirmation Failed";
		}
		$this->output($Return);
		exit;
	}

	public function add_payable()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('purchase_order_id');
		$this->load->model("Payable_model");
		$result = $this->Purchase_model->read_purchase_order1($id);
		$this->load->model('payable_model');
		// print_r($result);exit;
		$data = array(
			//'purchase_order_id ' => $result[0]->purchase_order_id,
			'project_id' => $result[0]->project_id,
			'total_amount' => $result[0]->total,
			'get_payment_methods' => $this->Xin_model->payment_methods(),
			'total_paid_amount' => $this->payable_model->payables_list($id),
			'get_gst' => $this->Xin_model->get_gst()->result()
		);
		// print_r($data);exit();
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/purchase/dialog_payable', $data);
		} else {
			redirect('admin/');
		}
	}
	public function get_term_details()
	{

		$id = $this->uri->segment('4');

		$get_data = $this->db->where('term_id', $id)->get('xin_term_condition')->result();

		echo json_encode($get_data);
	}


	///////////////////////////////////////////GRN MODULE///////////////////////////////////////////////
	public function grn_add()
	{
		if ($this->input->post('add_type') == 'add_grn') {
			// $this->db->where('qtn', 0)->or_where('remark', '')->or_where('date', '');
			// $this->db->delete('grn_log');

			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('type') == "new") {
				// print_r($_POST);exit;
				// if ($this->input->post("order1") == "") {
				// 	$Return['error'] = "Purchase Order Number Required";
				// } else 

				if ($this->input->post("supplier1") == "Select") {
					$Return['error'] = "Select Supplier";
				}
				if ($this->input->post("house1") == "") {
					$Return['error'] = "Select Warehouse";
				}
				if ($this->input->post("date1") == "") {
					$Return['error'] = "Date Required";
				}


				if ($Return['error'] != '') {
					$this->output($Return);
					exit;
				}
				$house_id = $this->input->post("house1");

				// Execute the query and handle potential errors
				$query = $this->db->select('project_id')->from('projects')->where('warehouse_id', $house_id)->get();

				if ($query === false) {
					// Handle query failure
					$db_error = $this->db->error();
					log_message('error', 'Database Query Failed: ' . $db_error['message']); // Log the error
					echo json_encode(['error' => 'Failed to fetch project ID. Please try again later.']);
					return; // Stop further execution
				}

				$house_toproj_id = $query->result();

				// Check if the result is empty
				if (empty($house_toproj_id)) {
					log_message('error', 'No project found for warehouse ID: ' . $house_id);
					$house_toproj_id = [(object)['project_id' => 0]]; // Default fallback to 0
				}

				// Proceed with the data preparation
				$grns_data = [
					'prjkt_id' => $house_toproj_id[0]->project_id ?? 0,
					'po_number' => 'M',
					'date' => $this->input->post("date1"),
					'created_by' => $_SESSION['username']['user_id'],
					'created_datetime' => date('Y-m-d h:i:s')
				];

				$grn_id = $this->Purchase_model->add_grn_data($grns_data); // Get the inserted GRN ID


				$currentMonth = date('ym');

				// Handle GRN sequence
				$this->db->trans_start(); // Start transaction

				// Check if sequence for the current month exists
				$sequence = $this->Purchase_model->get_grn_sequence($currentMonth);

				if ($sequence) {
					// Increment sequence
					$new_sequence = $sequence->sequence + 1;
					$this->Purchase_model->update_grn_sequence($currentMonth, $new_sequence);
				} else {
					// Initialize sequence for the new month
					$new_sequence = 1;
					$this->Purchase_model->insert_grn_sequence($currentMonth, $new_sequence);
				}

				$this->db->trans_complete(); // Complete transaction

				// Generate the new grn_no
				$new_grn_no = "OIS/GRN/" . date('ym') . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);

				// Update the grn_tbl with the new grn_no
				$this->db->update('grn_tbl', ['grn_no' => $new_grn_no], ['grn_id' => $grn_id]);

				$qtyn1 = 0;
				$rqtyn1 = 0;
				$product_iid1 = 0;

				for ($i = 0; $i < count($this->input->post('u_item')); $i++) {

					$qtyn1 += $this->input->post('u_qty_order')[$i];
					$rqtyn1 += $this->input->post('u_qty_rec')[$i];
					$product_iid1 += $this->input->post('u_item')[$i];

					$data_opt = array(
						'type' => 'product',
						'pur_order_id' => 'M',
						'prjct_id' => $house_toproj_id[0]->project_id ?? 0,
						'prd_id' => $this->input->post('u_item')[$i],
						'blank_unit' => $this->input->post('u_description')[$i],
						// 'prd_uom' => $this->input->post('uom')[$i],
						'sup_id' => $this->input->post('supplier1'),
						'grn_id' => $grn_id,
						'qty_need' => $this->input->post('u_qty_order')[$i],
						'qty_rec' => $this->input->post('u_qty_rec')[$i],
						'qty_rem' => $this->input->post('u_qty_rem')[$i],
						'prd_remark' => $this->input->post('u_remark')[$i],
						'prd_rec_date' => $this->input->post('date1'),
						'created_by' => $_SESSION['username']['user_id'],
						'created_datetime' => date('Y-m-d h:i:s'),
						'status' => ($this->input->post('u_qty_order')[$i] == $this->input->post('u_qty_rec')[$i]) ? 'Complete' : 'In Transit'
					);
					$this->db->insert('grn_item_mapping', $data_opt);

					$data_opt123 = array(
						'date' => $this->input->post('date1'),
						'item' => $this->input->post('u_item')[$i],
						// 'item_uom' => $this->input->post('uom')[$i],
						'qtn' => $this->input->post('u_qty_rec')[$i],
						'po' => 'M',
						'grn_id' => $grn_id,
						'remark' => $this->input->post('u_remark')[$i],
						'wh_no' => $this->input->post('house1'),
						'supplier' => $this->input->post('supplier1'),
						'need' => $this->input->post('u_qty_order')[$i],
						'rem' => $this->input->post('u_qty_rem')[$i]
					);

					$this->db->insert('grn_log', $data_opt123);
					$this->db->delete('grn_log', ['qtn' => 0]);

					//supplier to project movement 
					// if ($this->input->post('house1') == 'p') {
					// 	$stock_move_data = array(
					// 		'product_id' => $this->input->post('u_item')[$i],
					// 		'remark' => $this->input->post('u_remark')[$i],
					// 		'qtn' => $this->input->post('u_qty_rec')[$i],
					// 		'prj_id' => $this->input->post("projects1"),
					// 		'wh_id' => $this->input->post('house1'),
					// 		// 'item_uom' => $this->input->post('uom')[$i],
					// 		'trans_type' => 'INBOUND',
					// 		'trans_type' => 'Receive',
					// 		'stock_from' =>	 $this->input->post('supplier1'),
					// 		'stock_to' => $this->input->post("projects1"),
					// 		'from_to_type' => "supplier to project",
					// 		'created_date' => $this->input->post('date1'),
					// 		'by_whome' => $_SESSION['username']['user_id'],
					// 	);
					// 	$this->db->insert('stock_move_log', $stock_move_data);
					// 	$this->db->delete('stock_move_log', ['qtn' => 0]);
					// } else 
					if (is_numeric($this->input->post('house1'))) {
						$stock_move_data = array(
							'product_id' => $this->input->post('u_item')[$i],
							'remark' => $this->input->post('u_remark')[$i],
							'qtn' => $this->input->post('u_qty_rec')[$i],
							// 'prj_id' => $this->input->post("projects1"),
							// 'item_uom' => $this->input->post('uom')[$i],
							'wh_id' => $this->input->post('house1'),
							'trans_type' => 'INBOUND',
							'movement_type' => 'Receive',
							'stock_from' =>	 $this->input->post('supplier1'),
							'stock_to' => $this->input->post('house1'),
							'from_to_type' => "supplier to warehouse",
							'created_date' => date('Y-m-d', strtotime($this->input->post('date1'))) . ' ' . date('H:i:s'),
							'by_whome' => $_SESSION['username']['user_id'],

						);
						$this->db->insert('stock_move_log', $stock_move_data);
						$this->db->delete('stock_move_log', ['qtn' => 0]);
					}


					$row = $this->db->select('quantity, warehouse_id')
						->where('prd_id', $this->input->post('u_item')[$i])
						// ->where('uom_id', $this->input->post('uom')[$i])
						->where('warehouse_id', $this->input->post('house1'))
						->get('stock_management')
						->row();



					$new_qty = (int) $this->input->post('u_qty_rec')[$i];
					$new_warehouse_id = $this->input->post('house1');

					if ($row !== NULL) {
						// If the product exists with the same prd_id and warehouse_id, update the quantity
						$current_qty = $row->quantity;
						$total_qty = ($current_qty === NULL ? 0 : (int)$current_qty) + $new_qty;
						$this->db->update(
							'stock_management',
							['quantity' => $total_qty],
							[
								'prd_id' => $this->input->post('u_item')[$i],
								// 'uom_id' => $this->input->post('uom')[$i],
								'warehouse_id' => $new_warehouse_id
							]
						);
						// $this->db->update('product',['stock_qtn'=>$total_qty],['product_id'=>$this->input->post('prd_id1')[$i]]);
					} else {
						// Check if the product exists with the same prd_id but a different warehouse_id
						$row_diff_warehouse = $this->db->select('quantity')
							->where('prd_id', $this->input->post('u_item')[$i])
							// ->where('uom_id', $this->input->post('uom')[$i])
							->get('stock_management')
							->row();

						if ($row_diff_warehouse !== NULL) {
							// If the product exists but in a different warehouse, insert a new record for the new warehouse
							$data = array(
								'prd_id' => $this->input->post('u_item')[$i],
								// 'uom_id' => $this->input->post('uom')[$i],
								'quantity' => $new_qty,
								'warehouse_id' => $new_warehouse_id,
							);
							$this->db->insert('stock_management', $data);
						} else {
							// If the product does not exist at all, insert a new record
							$data = array(
								'prd_id' => $this->input->post('u_item')[$i],
								// 'uom_id' => $this->input->post('uom')[$i],
								'quantity' => $new_qty,
								'warehouse_id' => $new_warehouse_id,
							);
							$this->db->insert('stock_management', $data);
						}
					}
				}
				$this->db->set('grn_tbl.status', 'Complete');
				$this->db->where('grn_tbl.grn_id NOT IN (SELECT grn_id FROM grn_item_mapping WHERE qty_rem > 0)', NULL, FALSE);
				$this->db->update('grn_tbl');




				$Return['result'] = "GRN Added Successfully";
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	public function grn_view()
	{
		$session = $this->session->userdata('username');
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = 'GRN | ' . $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'GRN';
		$data['path_url'] = 'grn_list';
		// print_r($data);exit;
		if (!empty($session)) {
			// $this->db->where('qtn', 0)->or_where('remark', '')->or_where('date', '');
			// $this->db->delete('grn_log');
			$data['subview'] = $this->load->view("admin/grn/grn_list", $data, TRUE);
			$this->load->view('admin/layout/pms/layout_pms', $data); //page load
		} else {
			redirect('admin/');
		}
	}
	public function grn_list()
	{
		$session = $this->session->userdata('username');

		$data['title'] = 'GRN | ' . $this->Xin_model->site_title();
		$data['breadcrumbs'] = 'GRN';
		$data['path_url'] = 'grn_list';
		if (!empty($session)) {
			$this->load->view("admin/grn/grn_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$grn = $this->db->select('grn_tbl.*, purchase_order.porder_id,grn_log.wh_no, warehouse.w_name')
			->from('grn_tbl')
			->join('purchase_order', 'grn_tbl.po_number = purchase_order.purchase_order_id', 'left')
			->join('grn_log', 'grn_tbl.grn_id=grn_log.grn_id', 'left')
			->join('warehouse', 'grn_log.wh_no = warehouse.w_id', 'left')
			->group_by('grn_tbl.grn_id')
			->get();

		// print_r($grn->result());exit;

		$data = array();

		$i = 0;
		foreach ($grn->result() as $r) {
			$i++;


			$view = '<span data-toggle="tooltip" data-placement="top" title="View"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-purchase_order_id="' . $r->grn_id  . '"><span class="fa fa-eye"></span></button></span>';
			$edit = '<span data-toggle="tooltip" data-placement="top" title="Edit"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-grn_id="' . $r->grn_id  . '" data-order_id="' . $r->po_number . '"><span class="fa fa-pencil"></span></button></span>';
			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->grn_id   . '"><span class="fa fa-trash"></span></button></span>';
			// $download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/purchase/pdf_create/'.$r->purchase_order_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';

			// $combhr = $view . $delete;
			$combhr = $edit . $delete;

			$data[] = array(
				$i,
				$combhr,
				(is_numeric($r->wh_no)) ? $r->w_name : (($r->wh_no == "p") ? "Project Site" : ""),

				$r->grn_no,
				($r->po_number == 'M') ? "GRN Manual Entry" : $r->porder_id,
				date('d-M-Y', strtotime($r->date)),
				$r->status,
			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $grn->num_rows(),
			"recordsFiltered" => $grn->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}

	public function read_grn()
	{
		if ($this->input->get('edit_type') == 'edit_grn') {

			$data['title'] = $this->Xin_model->site_title();
			$id = $this->input->get('grn_id');

			$result = $this->db->select('*,grn_tbl.status as gtstatus')
				->from('grn_tbl')
				->join('grn_item_mapping', 'grn_tbl.grn_id=grn_item_mapping.grn_id')

				->where('grn_tbl.grn_id', $id)
				->get()->result();


			$newPOrder = $this->db->select('porder_id')->from('purchase_order')->where('purchase_order_id', $result[0]->pur_order_id)->get()->result();
			$data = array(
				'purchase_order_id' => ($result[0]->pur_order_id == 'M') ? 0 : $result[0]->pur_order_id,
				// 'proj_id'=>$result[0]->proj_id,
				'date' => $result[0]->date,
				'status' => $result[0]->gtstatus,
				'order_id' => ($result[0]->po_number == 'M') ? "GRN Manual Entry" : $newPOrder[0]->porder_id,
				'grn_id' => $result[0]->grn_no,
				'warehouse_id' => $result[0]->whouse,
				'warehouse' => $this->db->where('w_id', $result[0]->whouse)->get('warehouse')->result(),
				// product_uom_mapping.uom_name,
				'order_items' => $this->db->select('*, xin_suppliers.supplier_name,
																						  product.product_name,
																						  grn_item_mapping.status as go_status,
																						  grn_item_mapping.qty_need, 
																						  grn_item_mapping.qty_rem,
																						  grn_item_mapping.prd_remark') // Calculate the sum of quantity for each product
					->from('grn_tbl')
					->join('grn_item_mapping', 'grn_tbl.grn_id = grn_item_mapping.grn_id', 'left')
					// ->join('product_uom_mapping', 'grn_item_mapping.prd_uom = product_uom_mapping.uom_id', 'left')
					// ->join('grn_log', 'grn_tbl.grn_id = grn_log.grn_id')
					->join('xin_suppliers', 'grn_item_mapping.sup_id = xin_suppliers.supplier_id', 'left')
					->join('product', 'grn_item_mapping.prd_id = product.product_id', 'left')
					// ->where_not_in('grn_log.qtn', 0)
					->where('grn_tbl.grn_id', $id)
					// ->where('grn_item_mapping.po', $result[0]->pur_order_id)
					// ->group_by('grn_log.item')
					->get()
					->result(),
				// 'grn_log'=>
			);
			// print_r($data);exit;

			$session = $this->session->userdata('username');
			if (!empty($session)) {
				$this->load->view('admin/grn/dialog_grn', $data);
			} else {
				redirect('admin/');
			}
		}
	}

	public function grn_update1()
	{
		if ($this->input->post('edit_type') == 'edit_grn') {
			// Define return | here result is used to return user data and error for error message
			$res = $this->db->where('pur_order_id', $this->input->post('order_id'))->get('grn_item_mapping')->result();
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			// $errors = array(); // Initialize array to store errors

			if ($res) {
				if (count($this->input->post('prd_id1')) > 0) {
					$qtyn = 0;
					$rqtyn = 0;
					$allowed = array('png', 'jpg', 'jpeg', 'pdf', 'gif');
					$profile = "uploads/grn/";
					// $set_img = base_url() . "uploads/payment/";
					for ($i = 0; $i < count($this->input->post('prd_id1')); $i++) {
						$fname = '';

						if (!empty($_FILES['u_dofile1']['tmp_name'][$i]) && is_uploaded_file($_FILES['u_dofile1']['tmp_name'][$i])) {
							$filename = $_FILES['u_dofile1']['name'][$i];
							$ext = pathinfo($filename, PATHINFO_EXTENSION);

							if (in_array($ext, $allowed)) {
								$tmp_name = $_FILES["u_dofile1"]["tmp_name"][$i];
								$name = basename($_FILES["u_dofile1"]["name"][$i]);
								$newfilename = 'DO_' . round(microtime(true)) . '.' . $ext;

								if (move_uploaded_file($tmp_name, $profile . $newfilename)) {
									$fname = $newfilename;
								} else {
									$Return['error'] = $this->lang->line('xin_error_uploading_file');
									continue;
								}
							} else {
								$Return['error'] = $this->lang->line('xin_error_attatchment_type');
								continue;
							}
						}
						// if ($this->input->post('house2')[$i] == '') {
						// 	$errors[] = "Please Select Warehouse for product " . ($i + 1);
						// }
						// if ($this->input->post('r_prd_qtn1')[$i] == '') {
						//     $errors[] = "Please Enter Receive Value";
						// }

						// if (!empty($errors)) {
						// 	$Return['error'] = implode("<br>", $errors); // Join errors into a single string
						// 	$this->output($Return);
						// 	continue; // Skip the rest of the loop for this product
						// }


						// Error checks for each product
						// if ($this->input->post('house2')[$i] == '' && $this->input->post('r_prd_qtn1')[$i] !='') {
						// 	$errors[] = "Please Select Warehouse for product " . ($i + 1);
						// }
						// if ($this->input->post('r_prd_qtn1')[$i] == '') {
						//     $errors[] = "Please Enter Receive Value";
						// }

						// if (!empty($errors)) {
						// 	$Return['error'] = implode("<br>", $errors); // Join errors into a single string
						// 	$this->output($Return);
						// 	exit;
						// }

						$qtyn += (int) $this->input->post('w_prd_qtn1')[$i];
						$rqtyn +=  (int) $this->input->post('rem_prd_qtns')[$i] == 0;

						$data_opt = array(
							'pur_order_id' => $this->input->post('order_id'),
							'prd_id' => $this->input->post('prd_id1')[$i],
							// 'prd_uom' => $this->input->post('prd_uom1')[$i],
							'sup_id' => $this->input->post('sup_id1')[$i],
							'qty_need' => $this->input->post('w_prd_qtn1')[$i],
							'qty_rec' => $this->input->post('r_prd_qtn1')[$i],
							'qty_rem' => $this->input->post('rem_prd_qtn1')[$i],
							'prd_remark' => $this->input->post('u_rek1')[$i],
							'prd_rec_date' => $this->input->post('u_date1')[$i],
							// 'prd_rec_date' => date('Y-m-d'),
							'modified_by' => $_SESSION['username']['user_id'],
							'modified_datetime' => date('Y-m-d h:i:s'),
							'status' => ($this->input->post('rem_prd_qtns')[$i] == $this->input->post('r_prd_qtn1')[$i]) ? 'Complete' : 'In Transit'
						);

						$this->db->update(
							'grn_item_mapping',
							$data_opt,
							[
								'grn_id' => $this->input->post('grn_id'),
								'prd_id' => $this->input->post('prd_id1')[$i],
								// 'prd_uom' => $this->input->post('prd_uom1')[$i]
							]
						);

						$data_opt123 = array(
							'date' => date('Y-m-d'),
							'item' => $this->input->post('prd_id1')[$i],
							// 'item_uom' => $this->input->post('prd_uom1')[$i],
							'qtn' => $this->input->post('r_prd_qtn1')[$i],
							'po' => $this->input->post('order_id'),
							'grn_id' => $this->input->post('grn_id'),
							'remark' => $this->input->post('u_rek1')[$i],
							'do_no' => $this->input->post('u_do1')[$i],
							'do_file' => $fname,
							'wh_no' => $this->input->post('house2')[$i],
							'supplier' => $this->input->post('sup_id1')[$i],
							'need' => $this->input->post('w_prd_qtn1')[$i],
							'rem' => $this->input->post('rem_prd_qtn1')[$i]
						);

						$this->db->insert('grn_log', $data_opt123);
						$this->db->delete('grn_log', ['qtn' => 0]);

						//supplier to project movement 
						// if ($this->input->post('house2')[$i] == 'p') {
						// 	$stock_move_data = array(
						// 		'product_id' => $this->input->post('prd_id1')[$i],
						// 		'remark' => $this->input->post('u_rek1')[$i],
						// 		'qtn' => $this->input->post('r_prd_qtn1')[$i],
						// 		// 'prj_id' => $this->input->post('proj_id'),
						// 		'wh_id' => $this->input->post('house2')[$i],
						// 		'trans_type' => 'INBOUND',
						// 		'stock_from' =>	 $this->input->post('sup_id1')[$i],
						// 		'stock_to' => $this->input->post('proj_id'),
						// 		'from_to_type' => "supplier to project",
						// 		'created_date' => date('Y-m-d'),
						// 		'by_whome' => $_SESSION['username']['user_id']
						// 	);
						// 	$this->db->insert('stock_move_log', $stock_move_data);
						// } else 
						if (is_numeric($this->input->post('house2')[$i])) {
							$stock_move_data = array(
								'product_id' => $this->input->post('prd_id1')[$i],
								'remark' => $this->input->post('u_rek1')[$i],
								'qtn' => $this->input->post('r_prd_qtn1')[$i],
								'prj_id' => $this->input->post('proj_id'),
								'wh_id' => $this->input->post('house2')[$i],
								'trans_type' => 'INBOUND',
								'movement_type' => 'Receive',
								'stock_from' =>	 $this->input->post('sup_id1')[$i],
								'stock_to' => $this->input->post('house2')[$i],
								'from_to_type' => "supplier to warehouse",
								'created_date' => date('Y-m-d H:i:s'),
								'by_whome' => $_SESSION['username']['user_id']

							);
							// print_r($stock_move_data);exit;
							$this->db->insert('stock_move_log', $stock_move_data);
						}



						$row = $this->db->select('quantity, warehouse_id')
							->where('prd_id', $this->input->post('prd_id1')[$i])
							// ->where('uom_id', $this->input->post('prd_uom1')[$i])
							->where('warehouse_id', $this->input->post('house2')[$i])
							->get('stock_management')
							->row();

						$new_qty = (int) $this->input->post('r_prd_qtn1')[$i];
						$new_warehouse_id = $this->input->post('house2')[$i];

						if ($row !== NULL) {
							// If the product exists with the same prd_id and warehouse_id, update the quantity
							$current_qty = $row->quantity;
							$total_qty = ($current_qty === NULL ? 0 : (int)$current_qty) + $new_qty;
							$this->db->update(
								'stock_management',
								['quantity' => $total_qty],
								[
									'prd_id' => $this->input->post('prd_id1')[$i],
									// 'uom_id' => $this->input->post('prd_uom1')[$i],
									'warehouse_id' => $new_warehouse_id
								]
							);
							// $this->db->update('product',['stock_qtn'=>$total_qty],['product_id'=>$this->input->post('prd_id1')[$i]]);
						} else {
							// Check if the product exists with the same prd_id but a different warehouse_id
							$row_diff_warehouse = $this->db->select('quantity')
								->where('prd_id', $this->input->post('prd_id1')[$i])
								// ->where('uom_id', $this->input->post('prd_uom1')[$i])
								->get('stock_management')
								->row();

							if ($row_diff_warehouse !== NULL) {
								// If the product exists but in a different warehouse, insert a new record for the new warehouse
								$data = array(
									'prd_id' => $this->input->post('prd_id1')[$i],
									// 'uom_id' => $this->input->post('prd_uom1')[$i],
									'quantity' => $new_qty,
									'warehouse_id' => $new_warehouse_id,
								);
								$this->db->insert('stock_management', $data);
							} else {
								// If the product does not exist at all, insert a new record
								$data = array(
									'prd_id' => $this->input->post('prd_id1')[$i],
									// 'uom_id' => $this->input->post('prd_uom1')[$i],
									'quantity' => $new_qty,
									'warehouse_id' => $new_warehouse_id,
								);
								$this->db->insert('stock_management', $data);
							}
						}


						// $this->db->delete('stock_management', ['quantity' => 0]);
					}

					$this->db->set('grn_tbl.status', 'Complete');
					$this->db->where('grn_tbl.grn_id NOT IN (SELECT grn_id FROM grn_item_mapping WHERE qty_rem > 0)', NULL, FALSE);
					$this->db->update('grn_tbl');


					// if ($rqtyn == $qtyn) {
					// 	$this->db->update('grn_tbl', ['status' => 'Complete'], ['grn_id' => $this->input->post('grn_id')]);
					// } else {
					// 	$this->db->update('grn_tbl', ['status' => 'In Transit'], ['grn_id' => $this->input->post('grn_id')]);
					// }

					$Return['result'] = "Product Received";
				} else {
					$Return['error'] = $this->lang->line('xin_error_msg');
				}
			} else {
				$Return['error'] = "Invalid data format provided.";
			}

			$this->output($Return);
			exit;
		}
	}

	public function grn_delete()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$this->db->delete('grn_tbl', ['grn_id' => $id]);
		$this->db->delete('grn_item_mapping', ['grn_id' => $id]);
		$this->db->delete('grn_log', ['grn_id' => $id]);

		if (isset($id)) {
			$Return['result'] = "GRN Deleted Successfully";
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

	///////////////////////////////////////////GRN MODULE END///////////////////////////////////////////////
	public function get_project_address($id)
	{
		$result = $this->Purchase_model->get_project_address($id);
		echo json_encode($result);
	}

	public function get_tasks_by_milestone($project_id, $cat_id)
	{
		$result = $this->Purchase_model->get_quotationdata_from_project($project_id, $cat_id);
		// print_r($result);exit();
		echo json_encode($result);
	}
	public function add_expence_data()
	{
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();

		$allowed = array('png', 'jpg', 'jpeg', 'pdf', 'gif');
		$profile = "uploads/payment/";
		$set_img = base_url() . "uploads/payment/";

		foreach ($this->input->post('invoice_no') as $key => $val) {
			$fname = '';

			if (!empty($_FILES['payment_picture']['tmp_name'][$key]) && is_uploaded_file($_FILES['payment_picture']['tmp_name'][$key])) {
				$filename = $_FILES['payment_picture']['name'][$key];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);

				if (in_array($ext, $allowed)) {
					$tmp_name = $_FILES["payment_picture"]["tmp_name"][$key];
					$name = basename($_FILES["payment_picture"]["name"][$key]);
					$newfilename = 'payment_' . round(microtime(true)) . '.' . $ext;

					if (move_uploaded_file($tmp_name, $profile . $newfilename)) {
						$fname = $newfilename;
					} else {
						$Return['error'] = $this->lang->line('xin_error_uploading_file');
						continue;
					}
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
					continue;
				}
			}


			$expense_data = array(
				'expense_subt' => $this->input->post('invoice_subt')[$key],
				'expense_gst' => $this->input->post('invoice_gst')[$key],
				'expense_amount' => $this->input->post('invoice_amount')[$key],
				'purchase_invoice_no' => $this->input->post('invoice_no')[$key],
				'expense_date' => $this->input->post('date')[$key],
				'expense_attachment' => $fname,
				'do_no' => $this->input->post('do_no')[$key],
				'purchase_order_id' => ($this->input->post('purchase_order_id')) ?? 0,
				'purchase_order_no' => $this->input->post('porder_id')[$key],
				'project_id_subcon' => ($this->input->post('project_id_subcon')) ?? 0,

			);


			$expense_result = $this->db->insert('expenses', $expense_data);

			$payable_data = array(
				'invoice_no' => $this->input->post('invoice_no')[$key],
				'purchase_order_total' => $this->input->post('invoice_amount')[$key],
				// 'gst_num_po_total' => 0,
				'gst_on_po_total' => $this->input->post('invoice_gst')[$key],
				'after_gst_po_gt' => $this->input->post('invoice_amount')[$key],
				'total' => 0,
				'due_date' => ($this->input->post('poduedate')) ?? '',
				'manual_due_date' => ($this->input->post('manual_due_date')[$key]) ?? '',
				'amount' => 0,
				'remaining_amount' => $this->input->post('invoice_amount')[$key],
				'purchase_order_id' => ($this->input->post('purchase_order_id')) ?? 0,
				'project_id_subcon' => ($this->input->post('project_id_subcon')) ?? 0,
				'manual_po_number' => ($this->input->post('porder_id')[$key]) ?? 0,
				'subcon_id' => ($this->input->post('subcon_sup_id')) ?? 0,
				'exp_attachment' => $fname,
				'attachment' => $fname,
				'do_no' => $this->input->post('do_no')[$key],
				'created_datetime' => date('Y-m-d h:i:s'),
				'created_by' => $_SESSION['username']['user_id'],
				'flag' => 1,
			);

			$payable_result = $this->db->insert('xin_payable', $payable_data);

			if (!$expense_result || !$payable_result) {
				$Return['error'] = $this->lang->line('xin_error_msg');
				$this->output($Return);
				exit;
			}
		}

		$Return['result'] = "Purchase Expense Added Successfully";
		$this->output($Return);
		exit;
	}

	public function save_amd_line($id)
	{
		$quote_text = $this->input->post('quote_text');
		$this->db->update('purchase_order', ['amd_line' => $quote_text], ['purchase_order_id' => $id]);
		$Return['result'] = "Text Updated";
		$this->output($Return);
		exit;
	}
	public function save_contact_name($id)
	{
		$cantactperson = $this->input->post('contact_text');
		$this->db->update('purchase_order', ['cantactperson' => $cantactperson], ['purchase_order_id' => $id]);
		$Return['result'] = "Contact Person Updated";
		$this->output($Return);
		exit;
	}
	public function get_project_data_by_id($project_id)
	{
		$result = $this->db->where('project_id', $project_id)->get('project_addresses')->result();
		// print_r($result);exit();
		echo json_encode($result);
	}
}
