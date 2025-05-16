<?php



require_once(APPPATH . 'third_party/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options; // Import the Options class
class Finance extends My_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Xin_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Project_model");
		$this->load->model("Quotation_model");
		$this->load->model("Purchase_model");
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
	public function quotation_list()
	{
		$session = $this->session->userdata('username');
		$data['title'] = 'Quotation | ' . $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_units'] = $this->Xin_model->get_unit();
		$data['get_term_condition'] = $this->Xin_model->get_term_condition();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['get_all_customer'] = $this->Quotation_model->get_clients();
		$data['all_shipping_terms'] = $this->db->get('xin_shipping_term')->result();
		$data['all_payment_terms'] = $this->db->get('xin_payment_term')->result();
		$data['get_all_draft'] = $this->db->where('status', "Draft")->get('quotation')->result();
		$data['get_all_manage'] = $this->db->where('status', "Approved by Management")->get('quotation')->result();
		$data['get_all_cust_approve'] = $this->db->where('status', "Pending Customer’s Approval")->get('quotation')->result();
		$data['get_all_appove'] = $this->db->where('status', "Confirmed")->get('quotation')->result();
		$data['get_all_rejected'] = $this->db->where('status', "Declined")->get('quotation')->result();
		$data['get_all_expire'] = $this->db->where('status', "Expire")->get('quotation')->result();


		$data['breadcrumbs'] = 'Quotation';
		$data['path_url'] = 'quotation';
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('3001', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/finance/quotation_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function all_quotation_list()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = 'Quotation | ' . $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_units'] = $this->Xin_model->get_unit();
		$data['get_term_condition'] = $this->Xin_model->get_term_condition();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['get_all_customer'] = $this->Quotation_model->get_clients();

		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (!empty($session)) {
			$this->load->view("admin/finance/quotation_list", $data);
		} else {
			redirect('admin/');
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$quotation = $this->Quotation_model->quotation_list();
		//echo $this->db->last_query();exit;
		$data = array();

		$i = 0;
		foreach ($quotation->result() as $r) {
			$i++;
			if ($r->status == 'Draft') {
				$manage_approve = '<span data-toggle="tooltip" data-placement="top" title="Management Approve"><button type="button" class="btn btn-xs btn-primary" onClick="btn_confirm(' . $r->quotation_id . ');" data-quotation_id="' . $r->quotation_id . '"><span class="fa fa-check"></span></button></span>';
			} else {
				$manage_approve = '';
			}

			if ($r->status == 'Management Approved') {
				$cust_approved = '<span data-toggle="tooltip" data-placement="top" title="Send To Customer"><button type="button" class="btn btn-xs btn-primary" onClick="btn_cust_confirm(' . $r->quotation_id . ');" data-quotation_id="' . $r->quotation_id . '"><span class="fa fa-arrow-right"></span></button></span>';
				$approved = '<span data-toggle="tooltip" data-placement="top" title="Approved"><button type="button" class="btn btn-xs btn-primary" onClick="btn_approve(' . $r->quotation_id . ');" data-quotation_id="' . $r->quotation_id . '"><span class="fa fa-check"></span></button></span>';
			} else {
				$cust_approved = '';
				$approved = '';
			}

			if ($r->status == 'Pending Customer’s Approval') {
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><a href="' . site_url() . 'admin/finance/read_quotation/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-pencil"></span></button></a></span>';
				$approved3 = '<span data-toggle="tooltip" data-placement="top" title="Approved"><button type="button" class="btn btn-xs btn-success" onClick="btn_approve(' . $r->quotation_id . ');" data-quotation_id="' . $r->quotation_id . '"><span class="fa fa-check"></span></button></span>';
				$decline = '<span data-toggle="tooltip" data-placement="top" title="Rejected"><button type="button" class="btn btn-xs btn-danger" onClick="btn_reject(' . $r->quotation_id . ');" data-quotation_id="' . $r->quotation_id . '"><span class="fa fa-times"></span></button></span>';
			} else {
				$edit = '';
				$approved3 = '';
				$decline = '';
			}
			if ($r->status == 'Expired') {
				$edit2 = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><a href="' . site_url() . 'admin/finance/read_quotation/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-pencil"></span></button></a></span>';
			} else {
				$edit2 = '';
			}

			if ($r->status == 'Declined') {
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><a href="' . site_url() . 'admin/finance/read_quotation/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-pencil"></span></button></a></span>';
				$approved4 = '<span data-toggle="tooltip" data-placement="top" title="Approved"><button type="button" class="btn btn-xs btn-success" onClick="btn_approve(' . $r->quotation_id . ');" data-quotation_id="' . $r->quotation_id . '"><span class="fa fa-check"></span></button></span>';
			} else {
				$approved4 = '';
				$edit = '';
			}

			// if($r->status != "confirmed"){
			// //$project = '<span data-toggle="tooltip" data-placement="top" title="convert"><a href="javascript:void(0)" onclick="confirm('.$r->quotation_id.')"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
			// }else{
			// 	$project ='';
			// }

			$download = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/finance/pdf_create/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			// $edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><a href="'.site_url().'admin/finance/read_quotation/'.$r->quotation_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-pencil"></span></button></a></span>';
			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->quotation_id  . '"><span class="fa fa-trash"></span></button></span>';
			$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><a href="' . site_url() . 'admin/finance/read_quotation_view/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
			//$mail = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><a href="mailto:"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';

			//$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><a href="'.site_url().'admin/finance/read_quotation_view/'.$r->quotation_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light><span class="fa fa-eye"></span></button></span></a>';
			//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/finance/pdf_create/'.$r->quotation_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			//$combhr = $download.$edit.$delete;
			$combhr = $download . $edit . $edit2 . $delete . $view . $manage_approve . $cust_approved . $approved . $approved3 . $approved4 . $decline;

			$data[] = array(
				$i,
				$combhr,
				$r->quotation_no,
				$r->project_name,
				$r->f_name,
				date('d-m-Y', strtotime($r->created_datetime)),
				$r->status

			);
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $quotation->num_rows(),
			"recordsFiltered" => $quotation->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	public function get_customer_name($type)
	{
		$customerData = [];

		if ($type == "0") {
			$customerData["Company"] = $this->db->select('f_name,client_id')->where('cust_type', $type)->get('clients')->result();
		} elseif ($type == "1") {
			$customerData["Individual"] = $this->db->select('f_name,client_id')->where('cust_type', $type)->get('clients')->result();
		}

		// Output JSON response
		$this->output->set_content_type('application/json')->set_output(json_encode($customerData));
	}
	public function com_quote_list()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = 'Quotation | ' . $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_units'] = $this->Xin_model->get_unit();
		$data['get_term_condition'] = $this->Xin_model->get_term_condition();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['get_all_customer'] = $this->Quotation_model->get_clients();

		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (!empty($session)) {
			$this->load->view("admin/finance/quotation_list", $data);
		} else {
			redirect('admin/');
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$quotation = $this->Quotation_model->quotation_list();
		//echo $this->db->last_query();exit;
		$data = array();
		$i = 0;
		foreach ($quotation->result() as $r) {
			$i++;
			if ($r->quotation_for == "Company") {
				if ($r->status != "confirmed") {
					$project = '<span data-toggle="tooltip" data-placement="top" title="convert"><a href="javascript:void(0)" onclick="confirm(' . $r->quotation_id . ')"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-arrow-circle-right"></span></button></a></span>';
				} else {
					$project = '';
				}

				$download = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/finance/pdf_create/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
				$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><a href="' . site_url() . 'admin/finance/read_quotation/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-pencil"></span></button></a></span>';
				$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->quotation_id  . '"><span class="fa fa-trash"></span></button></span>';
				$view = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_view') . '"><a href="' . site_url() . 'admin/finance/read_quotation_view/' . $r->quotation_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
				//$mail = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><a href="mailto:"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';

				//$view = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_view').'"><a href="'.site_url().'admin/finance/read_quotation_view/'.$r->quotation_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light><span class="fa fa-eye"></span></button></span></a>';
				//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/finance/pdf_create/'.$r->quotation_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
				//$combhr = $download.$edit.$delete;
				$combhr = $download . $edit . $delete . $view . $project;

				$data[] = array(
					$i,
					$combhr,
					$r->quotation_no,
					$r->project_name,
					$r->f_name,
					date('d-m-Y', strtotime($r->created_datetime))

				);
			}
		}
		$output = array(
			"draw" => $draw,
			"recordsTotal" => $quotation->num_rows(),
			"recordsFiltered" => $quotation->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	public function add_quotation()
	{

		if ($this->input->post('add_type') == 'quotation') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			/* Server side PHP input validation */
			if ($this->input->post('project_id') === '') {
				$Return['error'] = $this->lang->line('xin_error_project_field');
			} else if ($this->input->post('customer_id') === '') {
				$Return['error'] = $this->lang->line('error_customer_required_field');
			} else if (!filter_var($this->input->post('pic_email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			} else if ($this->input->post('quotation_amount') === '') {
				$Return['error'] = $this->lang->line('error_amount_required_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			$data = array(
				'customer_id' 					=> $this->input->post('customer_id'),
				'bill_terms' 						=> $this->input->post('bill_terms'),
				'q_date' 				   => $this->input->post('proj_name'),
				'pic_name' 					       => $this->input->post('pic_name'),
				'pic_email' 					     => $this->input->post('pic_email'),
				'pic_phone' 				   => $this->input->post('pic_phone'),
				'bill_status' 							=> "draft",
				'quotation_for' 			 => $this->input->post('quotation_for'),
				'created_by' 					=> $_SESSION['username']['user_id'],
				'created_datetime'  	  => date('d-m-Y'),
			);

			$result = $this->Quotation_model->add($data);

			//  $this->db->insert('estimates',$data);
			// if ($result) {
			// 	$u_data=array(
			// 		'quotation_no'=>'PTS/QTN/'. date('Y/m').'/'.$result
			// 	);
			// 	$this->Quotation_model->update($u_data,$result);

			// 	if(isset($_POST['task_name']) && count($this->input->post('task_name')) > 0){
			// 		for($i=0;$i<count($this->input->post('task_name'));$i++){
			// 	$task_data = array(
			// 		'quotation_id' 		=> $result,
			// 		'task' 				=> $this->input->post('task_name')[$i],
			// 		'task_description' 		=> $this->input->post('task_description')[$i],
			// 		'created_by' 		=> $_SESSION['username']['user_id'],
			// 		'created_datetime'  => date('d-m-Y'),
			// 	);
			// 	$task_id=$this->Quotation_model->add_task($task_data);

			// 	if(count($this->input->post('description')) > 0){
			// 			for($j=0;$j<count($this->input->post('description'));$j++){
			// 					$subtask_data = array(
			// 						'quotation_id' 		=> $result,
			// 						'task_id' 			=> $task_id,
			// 						'description' 		=> $this->input->post('description')[$j],
			// 						'detail' 		=> $this->input->post('detail')[$j],
			// 						'unit_id' 		=> $this->input->post('unit_id')[$j],
			// 						'unit_rate' 		=> $this->input->post('unit_rate')[$j],
			// 						'created_by' 		=> $_SESSION['username']['user_id'],
			// 						'created_datetime'  => date('d-m-Y'),
			// 					);
			// 					$task_id=$this->Quotation_model->add_subtask($subtask_data);
			// 				}
			// 			}
			// 		}

			// 	}

			if ($result) {
				$u_data = array('quotation_no' => 'PTS/QTN/' . date('Y/m') . '/' . $result);
				$this->Quotation_model->update($u_data, $result);
				$Return['result'] = $this->lang->line('xin_success_quotation_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
		}
	}
	public function read_quotation($id)
	{
		$session = $this->session->userdata('username');
		$data['title'] = 'Quotation | ' . $this->Xin_model->site_title();
		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();
		$data['get_all_customer'] = $this->Quotation_model->get_clients();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_units'] = $this->Xin_model->get_unit();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['get_all_task'] = $this->Quotation_model->get_tasks($id);
		$data['get_all_subtasks'] = $this->Quotation_model->get_subtasks($id);

		$data['breadcrumbs'] = 'Quotation';
		$data['path_url'] = 'quotation';
		$data['result'] = $this->Quotation_model->read_quotation($id);

		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('3001', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/finance/get_quotation_detail", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function read_quotation_view($id)
	{
		$session = $this->session->userdata('username');
		$data['title'] = 'Quotation | ' . $this->Xin_model->site_title();
		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();
		$data['get_all_customer'] = $this->Quotation_model->get_clients();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_units'] = $this->Xin_model->get_unit();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$data['get_all_task'] = $this->Quotation_model->get_tasks($id);
		$data['get_all_subtasks'] = $this->Quotation_model->get_subtasks($id);

		$data['breadcrumbs'] = 'Quotation';
		$data['path_url'] = 'quotation';
		$data['result'] = $this->Quotation_model->read_quotation($id);

		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('3001', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/finance/view_quotation_detail", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function update_quotation()
	{
		if ($this->input->post('edit_type') == 'quotation') {
			$id = $this->input->post('quotation_id');

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('project_id') === '') {
				$Return['error'] = $this->lang->line('xin_error_project_field');
			} else if ($this->input->post('customer_id') === '') {
				$Return['error'] = $this->lang->line('error_customer_required_field');
			} else if (!filter_var($this->input->post('pic_email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			} else if ($this->input->post('quotation_amount') === '') {
				$Return['error'] = $this->lang->line('error_amount_required_field');
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			$data = array(
				'customer_id' 					=> $this->input->post('customer_id'),
				'term_condition' 				=> $this->input->post('term_condition_id'),
				'term_condition_description' 	=> $this->input->post('terms_condition'),
				'project_name' 					=> $this->input->post('proj_name'),
				'pic_name' 					    => $this->input->post('pic_name'),
				'pic_email' 					 => $this->input->post('pic_email'),
				'pic_phone' 				   => $this->input->post('pic_phone'),
				'status' 							=> "Draft",
				'quotation_for' 			 => $this->input->post('quotation_for'),
				'created_by' 					=> $_SESSION['username']['user_id'],
				'created_datetime'  	  => date('d-m-Y'),
			);
			$result = $this->Quotation_model->update($data, $id);

			if ($result) {
				if (isset($_POST['task_name']) && count($this->input->post('task_name')) > 0) {
					$this->Quotation_model->delete_task($id);
					$this->Quotation_model->delete_subtask($id);

					for ($i = 0; $i < count($this->input->post('task_name')); $i++) {
						$task_data = array(
							'quotation_id' 		=> $id,
							'task' 				=> $this->input->post('task_name')[$i],
							'task_description' 	=> $this->input->post('task_description')[$i],
							'created_by' 		=> $_SESSION['username']['user_id'],
							'created_datetime'  => date('d-m-Y'),
							'modified_by' 		=> $_SESSION['username']['user_id'],
							'modified_datetime'  => date('d-m-Y'),
						);
						$task_id = $this->Quotation_model->add_task($task_data);

						if (count($this->input->post('description')) > 0) {
							for ($j = 0; $j < count($this->input->post('description')); $j++) {
								$subtask_data = array(
									'quotation_id' 		=> $id,
									'task_id' 			=> $task_id,
									'description' 		=> $this->input->post('description')[$j],
									'detail' 		=> $this->input->post('detail')[$j],
									'unit_id' 		=> $this->input->post('unit_id')[$j],
									'unit_rate' 		=> $this->input->post('unit_rate')[$j],
									'created_by' 		=> $_SESSION['username']['user_id'],
									'created_datetime'  => date('d-m-Y'),
									'modified_by' 		=> $_SESSION['username']['user_id'],
									'modified_datetime'  => date('d-m-Y'),
								);
								$task_id = $this->Quotation_model->add_subtask($subtask_data);
							}
						}
					}
				}
				$Return['result'] = $this->lang->line('xin_success_quotation_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);

			exit;
		}
	}
	public function delete_quotation()
	{

		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Quotation_model->delete($id);
		$this->Quotation_model->delete_task($id);
		$this->Quotation_model->delete_subtask($id);


		if (isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_quotation_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}

	public function pdf_create()
	{


		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);


		// $data['get_all_projects'] = $this->Xin_model->get_all_project();
		// $data['all_suppliers'] = $this->Xin_model->all_suppliers();

		// $data['all_countries'] = $this->Xin_model->get_countries();
		// $data['get_gst'] = $this->Xin_model->get_gst();
		$get_quotations = $this->db->select('quotation.*,clients.f_name,clients.client_company_name')->join('clients', 'quotation.customer_id=clients.client_id')->where('quotation_id', $id)->get('quotation')->result();
		// $this->Quotation_model->read_quotation_for_inv($id);
		// echo "<pre>";print_r($get_quotations);exit;
		$data = array(
			'quotation_id ' => $get_quotations[0]->quotation_id,
			'quotation_no' => $get_quotations[0]->quotation_no,
			'quotation_amount' => $get_quotations[0]->total,

			'customer_id' => $get_quotations[0]->quote_for,
			'client_company_name' => $get_quotations[0]->client_company_name,
			'f_name' => $get_quotations[0]->f_name,
			'project_name' => $get_quotations[0]->q_title,
			'q_date' => $get_quotations[0]->q_date,
			'term_condition' => $get_quotations[0]->term_condition_description,
			'status' => $get_quotations[0]->status,
			'created_datetime' => $get_quotations[0]->created_datetime,
			'address' => $get_quotations[0]->project_s_add,
			'get_all_task' => $this->Quotation_model->get_tasks($id),
			'get_all_subtasks' => $this->Quotation_model->get_subtasks($id),
			'all_units' => $this->Xin_model->get_unit(),
			'quote_pic' => $get_quotations[0]->pic_name,
			'quote_email' => $get_quotations[0]->pic_email,
			'quote_phone' => $get_quotations[0]->pic_phone,
			'total_item_amount' => $get_quotations[0]->total_item_amount,
			'gst' => $get_quotations[0]->gst,
			'gst_value' => $get_quotations[0]->gst_value,
		);

		$this->load->view('admin/finance/get_quotation_receipt', $data);
	}
	public function credit_pdf_create()
	{


		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);


		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();

		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['get_gst'] = $this->Xin_model->get_gst();
		$result = $this->Quotation_model->read_credit($id);
		//echo $this->db->last_query();
		$data = array(
			'credit_id ' => $result[0]->credit_id,
			'invoice_id' => $result[0]->invoice_id,
			'project_id' => $result[0]->project_id,
			'invoice_date' => $result[0]->invoice_date,
			'invoice_no' => $result[0]->invoice_no,
			'invoice_due_date' => $result[0]->invoice_due_date,
			'client_id' => $result[0]->client_id,
			'terms' => $result[0]->terms,
			'term_id' => $result[0]->term_id,
			'sub_total' => $result[0]->sub_total,
			'gst' => $result[0]->gst,
			'gst_value' => $result[0]->gst_value,
			'total' => $result[0]->total,
			'bill_status' => $result[0]->bill_status,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			'get_all_project' => $this->Xin_model->get_all_project(),
			'get_all_customer' => $this->Xin_model->all_customer(),
			'client_company_name' => $result[0]->client_company_name,
			'client_phone' => $result[0]->client_phone,
			'address' => $result[0]->address,
			'project_title' => $result[0]->project_title,
			'attn_name' => $result[0]->attn_name,
			'clientid' => $result[0]->client_id,
			'is_gst_inclusive' => $result[0]->is_gst_inclusive,
			'project_clientid' => $result[0]->project_clientid,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			// 'get_all_suppliers' => $this->Xin_model->all_suppliers(),
			// 'get_all_countries' => $this->Xin_model->get_countries(),
			// 'get_all_packings' => $this->Xin_model->get_packing_type()->result(),
			// 'get_payment_methods' => $this->Xin_model->get_payment_method()->result(),
			'get_all_items' => $this->Quotation_model->read_credit_item($result[0]->credit_id),
			'get_gst' => $this->Xin_model->get_gst(),
			'all_payment_terms' => $this->Xin_model->get_payment_term(),
			'quotation_result' => $this->Quotation_model->ajax_project_quotation_info($result[0]->project_id),
			'invoice_result' => $this->Quotation_model->ajax_invoice_info($result[0]->project_id)
		);
		$this->load->view('admin/finance/get_credit_receipt', $data);
	}
	public function get_quotation_receipt() {}

	public function get_supplier_address()
	{

		$data['title'] = $this->Xin_model->site_title();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$id = $this->uri->segment(4);

		$data = array(
			'client_id' => $id
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/finance/get_supplier_address", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	public function add_tasks()
	{
		$session = $this->session->userdata('username');
		$data['breadcrumbs'] = 'Quotation';
		$data['path_url'] = 'quotation';
		$data['title'] = 'Quotation | ' . $this->Xin_model->site_title();
		$data['subview'] = $this->load->view("admin/finance/get_task", $data, TRUE);
		$this->load->view('admin/layout/pms/layout_pms', $data); //page load
	}

	public function cust_confirm()
	{
		$id = $this->uri->segment(4);
		$u_data = array(
			'status' => 'Pending Customer’s Approval'
		);
		// $this->Quotation_model->update($u_data,$id);
		$this->db->update('crm_quotation', $u_data, ['quotation_id' => $id]);
	}

	public function btn_decline()
	{
		$id = $this->uri->segment(4);
		$u_data = array(
			'status' => 'Declined'
		);
		// $this->Quotation_model->update($u_data,$id);
		$this->db->update('crm_quotation', $u_data, ['quotation_id' => $id]);
	}
	public function btn_approve()
	{
		$id = $this->uri->segment(4);
		$u_data = array(
			'status' => 'Approved'
		);

		$this->db->update('crm_quotation', $u_data, ['quotation_id' => $id]);
	}
	// public function get_details(){
	// 	$id= $this->uri->segment(4);
	// 	$u_data=array(
	// 		'status'=>'Management Approved'
	// 	);

	// 	$this->db->update('crm_quotation',$u_data,['quotation_id'=>$id]);



	// }
	public function get_details()
	{
		$id = $this->uri->segment(4);
		$u_data = array(
			'status' => 'confirmed'
		);
		$this->Quotation_model->update($u_data, $id);
		$quotation = $this->Quotation_model->read_quotation($id);
		$task = $this->Quotation_model->get_tasks($id);
		$subtasks = $this->Quotation_model->get_subtasks($id);

		$data = array(
			'quotation_no' => $quotation[0]->quotation_no,
			'project_title' => $quotation[0]->project_name,
			'letter_acceptance_no' => $quotation[0]->letter_acceptance_no,
			'project_billing_rate' => $quotation[0]->quotation_amount,
			'project_clientid ' => $quotation[0]->customer_id,
			'project_created' => date('Y-m-d h:i:s'),
			'attn_name' => $quotation[0]->attn_name,
		);
		$project_id = $this->Quotation_model->add_project($data);

		if (count($task) > 0) {
			for ($i = 0; $i < count($task); $i++) {
				$task_data = array(
					'task_created' => date('Y-m-d h:i:s'),
					'task_creatorid' => $_SESSION['username']['user_id'],
					'task_clientid' => $quotation[0]->customer_id,
					'task_projectid' => $project_id,
					'task_title' => $task[$i]->task,
					'task_description' => $task[$i]->task_description

				);
				$task_id = $this->Quotation_model->add_project_task($task_data);

				if (count($subtasks) > 0) {
					for ($j = 0; $j < count($subtasks); $j++) {
						$subtask_data = array(
							'subtask_projectid' 		=> $project_id,
							'subtask_taskid' 			=> $task_id,
							'subtask_clientid' 			=> $quotation[0]->customer_id,
							'subtask_description' 		=> $subtasks[$j]->description,
							'subtask_detail' 			=> $subtasks[$j]->detail,
							'unit_id' 					=> $subtasks[$j]->unit_id,
							'unit_rate' 				=> $subtasks[$j]->unit_rate,
							'subtask_creatorid' 		=> $_SESSION['username']['user_id'],
							'subtask_created'  			=> date('Y-m-d h:i:s'),
						);
						$subtask_id = $this->Quotation_model->add_project_subtask($subtask_data);
					}
				}
			}
		}
	}
	public function get_customer_address()
	{

		$data['title'] = $this->Xin_model->site_title();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$id = $this->uri->segment(4);

		$data = array(
			'project_id' => $id
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/finance/get_invoice_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	public function get_invoice_customer_address()
	{

		$data['title'] = $this->Xin_model->site_title();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$id = $this->uri->segment(4);

		$data = array(
			'project_id' => $id
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/finance/get_customer_address", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	public function get_invoice_detail()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$id = $this->uri->segment(4);

		$data = array(
			'invoice_id' => $id
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/finance/get_all_invoice_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	public function get_quotation_detail()
	{
		$data['title'] = $this->Xin_model->site_title();
		$data['all_countries'] = $this->Xin_model->get_countries();
		$id = $this->uri->segment(4);

		$data = array(
			'project_id' => $id
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view("admin/finance/get_product_quotation_detail", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
	}
	public function invoice_list()
	{
		$session = $this->session->userdata('username');
		$data['title'] = 'Invoice | ' . $this->Xin_model->site_title();
		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['all_units'] = $this->Xin_model->get_unit();
		$data['get_gst'] = $this->Xin_model->get_gst()->result();
		$data['all_payment_terms'] = $this->Xin_model->get_payment_term();
		$data['all_customer'] = $this->Quotation_model->get_crm_customer();
		$data['all_quotation'] = $this->Quotation_model->get_estimate_quotation();

		$data['breadcrumbs'] = 'Invoice';
		$data['path_url'] = 'invoice';
		$role_resources_ids = $this->Xin_model->user_role_resource();

		if (in_array('3101', $role_resources_ids)) {
			if (!empty($session)) {
				$data['subview'] = $this->load->view("admin/finance/invoice_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
	}
	public function all_invoice_list()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$data['title'] = 'Invoice | ' . $this->Xin_model->site_title();
		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['get_gst'] = $this->Xin_model->get_gst()->result();
		$data['all_payment_terms'] = $this->Xin_model->get_payment_term();

		$data['all_quotation'] = $this->db->get('estimates')->result();
		$data['all_customer'] = $this->db->get('clients')->result();
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (!empty($session)) {
			$this->load->view("admin/finance/invoice_list", $data);
		} else {
			redirect('admin/');
		}
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));


		$invoice = $this->Quotation_model->invoice_list();
		// print_r($invoice->result());exit;

		$data = array();

		foreach ($invoice->result() as $r) {
			$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-invoice_id="' . $r->invoice_id  . '"><span class="fa fa-pencil"></span></button></span>';
			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->invoice_id  . '"><span class="fa fa-trash"></span></button></span>';
			$receivable = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_receivable') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-invoice_id="' . $r->invoice_id  . '"><span class="fa fa-arrow-circle-right"></span></button></span>';
			$download = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/finance/invoice_pdf_create/' . $r->invoice_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			$view = '<span data-toggle="tooltip" data-placement="top" title="View"><a href="' . site_url() . 'admin/finance/view_invoice/' . $r->invoice_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
			//$combhr = $download.$edit.$delete;
			$combhr = $edit . $delete . $view . $download . $receivable;

			$data[] = array(
				$combhr,
				$r->invoice_no,
				$r->project_title,
				($r->f_name) ?? $r->client_company_name,
				date('d-m-Y', strtotime($r->created_datetime))

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
	public function add_receivable()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('invoice_id');
		$data['get_gst'] = $this->Xin_model->get_gst()->result();

		$data['result'] = $this->Quotation_model->read_invoice($id);
		$data['total_paid_amount'] = $this->Receivable_model->read_invoice($id);
		$data['get_receivables'] = $this->Receivable_model->get_receivable($id);
		$data['get_payment_methods'] = $this->Xin_model->get_payment_method()->result();

		//  print_r($data);exit;
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/finance/dialog_receivable', $data);
		} else {
			redirect('admin/');
		}
	}

	public function get_quotation_from_project($project_id)
	{
		$data['quotation_no'] = $this->db->select('project_sn,quotation_no,project_clientid,supervisor,project_address')->from('projects')->where('project_id', $project_id)->get()->result();
		$data['milestone_list'] = $this->db->select('task_cat_id')->from('tasks')->where('task_projectid', $project_id)->group_by('task_cat_id')->get()->result();

		// print_r($data['milestone_list']);exit;
		// Set the content type to JSON
		$this->output->set_content_type('application/json');

		// Encode the data as JSON and output it
		$this->output->set_output(json_encode($data));
	}

	public function get_task_from_milestone($milestone_id, $project_id)
	{
		$data['task_list'] = $this->db->select('task_id, task_title')->from('tasks')->where('task_cat_id', $milestone_id)->where('task_projectid', $project_id)->where('tasks.task_status !=', 'completed')->get()->result();


		// Set the content type to JSON
		$this->output->set_content_type('application/json');

		// Encode the data as JSON and output it
		$this->output->set_output(json_encode($data));
	}

	public function add_invoice()
	{
		if ($this->input->post('add_type') == 'invoice') {
			// print_r($_POST);exit();
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$total_invoice_amount = floatval($this->input->post('previous_invoice_amount'))	+ floatval($this->input->post('sub_total'));
			/* Server side PHP input validation */
			// echo "<pre>";
			// print_r($this->input->post('quotation_amount'));exit;
			$id = $this->input->post('quotation_no');
			$estimateData = $this->Quotation_model->get_qoutation_no($id);
			//$this->input->post('quotation_amount')
			// if (floatval($estimateData[0]->bill_final_amount) < floatval($total_invoice_amount)) {
			// $Return['error'] = $this->lang->line('xin_error_quotation_exceed');
			//} else 
			if ($this->input->post('project_id') === '') {
				$Return['error'] = "Select Project";
			} else if ($this->input->post('client_id') === '') {
				$Return['error'] = "Select Customer";
			} else if ($this->input->post('invoice_date') === '') {
				$Return['error'] = "Select Invoice Date";
			} else if ($this->input->post('payment_terms') === '') {
				$Return['error'] = $this->lang->line('xin_error_payment_term_field');
			} else if ($this->input->post('due_date') === '') {
				$Return['error'] = $this->lang->line('xin_error_due_date_field');
			} else if ($this->input->post('inclusive_gst') != 'on') {
				if ($this->input->post('order_gst2') == 'Select') {
					$Return['error'] = "Please Select GST";
				}
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			$id = $this->input->post('quotation_no');
			$qoutation_no = $this->Quotation_model->get_qoutation_no($id);
			$def_gst = $this->db->select('d_gst')->from('xin_system_setting')->get()->result();
			$data = array(
				'm_do_no' => $this->input->post('m_do_no'),
				'm_order_no' => $this->input->post('m_order_no'),
				'mile_id' => $this->input->post('milestone'),
				'task_id' => $this->input->post('task'),
				'billing_addresses' => $this->input->post('bill_address'),
				'shipping_addresses' => $this->input->post('delivery_address'),
				'quotation_no'      => ($this->input->post('qt_nos') == '' || $this->input->post('qt_nos') == 0) ? $this->input->post('qt_nos') : 0,
				'bill_estimateid ' 	=> $this->input->post('qt_id'),
				'total'             => $this->input->post('grandtotal'),
				'client_id'         => $this->input->post('client_id'),
				'project_id'        => $this->input->post('project_id'),
				'invoice_date'      => $this->input->post('invoice_date'),
				'terms'             => $this->input->post('payment_term'),
				'invoice_due_date'  => $this->input->post('due_date'),
				'is_gst_inclusive' 	=> $this->input->post('inclusive_gst') ?? 'off',
				'gst'               => ($this->input->post('order_gst2') == 'Select') ? $def_gst[0]->d_gst : $this->input->post('order_gst2'),
				'discount'          => $this->input->post('discount2'),
				'sub_total'         => $this->input->post('sub_t'),
				'gst_value'         => ($this->input->post('order_gst2') == 'Select') ? $this->input->post('d_gst_i') : $this->input->post('g_val'),
				'total'         	=> $this->input->post('t'),
				'cterm'				=> $this->input->post('cterm'),
				'created_by'        => $_SESSION['username']['user_id'],
				'created_datetime'  => date('Y-m-d h:i:s'),
			);
			// echo "<pre>";
			// print_r($this->input->post());
			// exit;
			$result = $this->Quotation_model->add_invoice($data);


			if ($result) {


				$currentMonth = date('ym'); // Get current year and month in 'YYMM' format

				// Handle sequence
				$this->db->trans_start(); // Start transaction

				// Check if sequence for the current month exists
				$sequence = $this->Purchase_model->get_current_inv_sequence($currentMonth);

				if ($sequence) {
					// Increment sequence
					$new_sequence = $sequence->sequence + 1;
					$this->Purchase_model->update_inv_sequence($currentMonth, $new_sequence);
				} else {
					// Initialize sequence for the new month
					$new_sequence = 1;
					$this->Purchase_model->insert_inv_sequence($currentMonth, $new_sequence);
				}

				$this->db->trans_complete(); // Complete transaction

				// Generate the new porder_id
				$new_inv_id = "INV " . $currentMonth . str_pad($new_sequence, 3, '0', STR_PAD_LEFT);

				$u_data = array(
					'invoice_no' => $new_inv_id
				);

				$this->Quotation_model->update_invoice($u_data, $result);



				$types = $this->input->post('type');
				$u_items = $this->input->post('u_item');
				$descriptions = $this->input->post('item_description');
				$units = $this->input->post('unit');
				$rates = $this->input->post('rate');
				$quantities = $this->input->post('quantity');
				$totals = $this->input->post('total');

				if (count($totals) > 0) {
					for ($i = 0; $i < count($totals); $i++) {
						// Ensure all arrays have the same length and avoid accessing undefined indexes
						if (isset($types[$i], $units[$i], $rates[$i], $quantities[$i], $totals[$i])) {
							$invoice_item = [
								'invoice_id' => $result,
								'item' => $u_items[$i] ?? '',
								'job_description' => ($types[$i] == 'plain') ? ($descriptions[$i] ?? '') : '',
								'unit' => $units[$i],
								'rate' => $rates[$i],
								'item_qtn' => $quantities[$i],
								'item_type' => $types[$i],
								'total' => $totals[$i],
								'created_by' => $_SESSION['username']['user_id'],
								'created_datetime' => date('Y-m-d h:i:s'),
							];
							$this->Quotation_model->add_invoice_item($invoice_item);
						}
					}
				}

				$Return['result'] = $this->lang->line('xin_success_invoice_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
		}
	}

	public function add_credit()
	{
		if ($this->input->post('add_type') == 'credit') {

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			$total_invoice_amount = floatval($this->input->post('quotation_amount'))	+ floatval($this->input->post('sub_total'));
			/* Server side PHP input validation */
			if ($this->input->post('project_id') === '') {
				$Return['error'] = $this->lang->line('xin_error_project_field');
			} else if ($this->input->post('is_gst') != "1") {
				if ($this->input->post('gst') == "") {
					$Return['error'] = "GST is Required Field";
				}
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			$data = array(
				'client_id' 		=> $this->input->post('client_id'),
				'project_id' 		=> $this->input->post('project_id'),
				'invoice_id' 		=> $this->input->post('invoice_id'),
				'terms' 			=> $this->input->post('term_condition'),
				// 'term_id' 			=> $this->input->post('term_id'),
				'invoice_date'      => $this->input->post('invoice_date'),
				'invoice_due_date' 	=> $this->input->post('due_date'),
				'sub_total' 		=> $this->input->post('sub_total'),
				'is_gst_inclusive' =>  $this->input->post('is_gst'),
				'gst' 				=> $this->input->post('gst'),
				'gst_value'			=> $this->input->post('sub_total') * ($this->input->post('gst') / 100),
				'total' 			=> $this->input->post('total_amount'),
				'created_by' 		=> $_SESSION['username']['user_id'],
				'created_datetime'  => date('Y-m-d h:i:s'),
			);

			$result = $this->Quotation_model->add_credit_notes($data);
			if ($result) {

				if (isset($_POST['description']) && count($this->input->post('description')) > 0) {
					for ($i = 0; $i < count($this->input->post('description')); $i++) {
						$item_data = array(
							'credit_note_id' 		=> $result,
							'job_description' 	=> $this->input->post('description')[$i],
							'cost' 				=> $this->input->post('cost')[$i],
							'total' 			=> $this->input->post('amount')[$i],
							'created_by' 		=> $_SESSION['username']['user_id'],
							'created_datetime'  => date('d-m-Y'),
						);
						$this->Quotation_model->add_credit_item($item_data);
					}
				}
				$Return['result'] = $this->lang->line('xin_success_credit_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}

			$this->output($Return);
			exit;
		}
	}
	public function read_credit()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('credit_id');

		$result = $this->Quotation_model->read_credit($id);

		$data = array(
			'credit_id ' => $result[0]->credit_id,
			'invoice_id' => $result[0]->invoice_id,
			'project_id' => $result[0]->project_id,
			'invoice_date' => $result[0]->invoice_date,
			'invoice_due_date' => $result[0]->invoice_due_date,
			'client_id' => $result[0]->client_id,
			'terms' => $result[0]->terms,
			'term_id' => $result[0]->term_id,
			'sub_total' => $result[0]->sub_total,
			'gst' => $result[0]->gst,
			'gst_value' => $result[0]->gst_value,
			'total' => $result[0]->total,
			'bill_status' => $result[0]->bill_status,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			'get_all_project' => $this->Xin_model->get_all_project(),
			'get_all_customer' => $this->Xin_model->all_customer(),
			'client_company_name' => $result[0]->client_company_name,
			'client_phone' => $result[0]->client_phone,
			'address' => $result[0]->address,
			'project_title' => $result[0]->project_title,
			'attn_name' => $result[0]->attn_name,
			'clientid' => $result[0]->client_id,
			'is_gst_inclusive' => $result[0]->is_gst_inclusive,
			'project_clientid' => $result[0]->project_clientid,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			// 'get_all_suppliers' => $this->Xin_model->all_suppliers(),
			// 'get_all_countries' => $this->Xin_model->get_countries(),
			// 'get_all_packings' => $this->Xin_model->get_packing_type()->result(),
			// 'get_payment_methods' => $this->Xin_model->get_payment_method()->result(),
			'get_all_items' => $this->Quotation_model->read_credit_item($result[0]->credit_id),
			'get_gst' => $this->Xin_model->get_gst(),
			'all_payment_terms' => $this->Xin_model->get_payment_term(),
			'quotation_result' => $this->Quotation_model->ajax_project_quotation_info($result[0]->project_id),
			'invoice_result' => $this->Quotation_model->ajax_invoice_info($result[0]->project_id)
		);
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($data);exit;
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/finance/dialog_credit', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update_credit()
	{

		if ($this->input->post('edit_type') == 'credit') {
			$id = $this->input->post('credit_id');
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();
			if ($this->input->post('u_project_id') === '') {
				$Return['error'] = $this->lang->line('xin_error_project_field');
			} else if ($this->input->post('is_gst1') != "1") {
				if ($this->input->post('u_gst') == "") {
					$Return['error'] = "GST is Required Field";
				}
			}

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			$data = array(
				'project_id' 		=> $this->input->post('u_project_id'),
				'invoice_date' 		=> $this->input->post('u_invoice_date'),
				'term_id' 			=> $this->input->post('payment_term'),
				'invoice_due_date' 	=> $this->input->post('u_due_date'),
				'client_id' 		=> $this->input->post('client_id'),
				'sub_total' 		=> $this->input->post('u_sub_total'),
				'is_gst_inclusive'  => $this->input->post('is_gst1'),
				'gst' 				=> $this->input->post('u_gst'),
				'gst_value' 		=> $this->input->post('u_sub_total') * ($this->input->post('u_gst') / 100),
				'total' 			=> $this->input->post('u_total_amount'),
				'bill_status'		=> $this->input->post('bill_status'),
				'modified_by' 		=> $_SESSION['username']['user_id'],
				'modified_datetime'  => date('d-m-Y'),
			);

			$result = $this->Quotation_model->update_credit($data, $id);

			if ($result) {
				if (isset($_POST['u_description']) && count($this->input->post('u_description')) > 0) {
					$this->Quotation_model->delete_credit_items($id);

					for ($i = 0; $i < count($this->input->post('u_description')); $i++) {
						$item_data = array(
							'credit_note_id' 		=> $id,
							'job_description' 	=> $this->input->post('u_description')[$i],
							'cost' 				=> $this->input->post('u_cost')[$i],
							'total' 			=> $this->input->post('u_amount')[$i],
							'created_by' 		=> $_SESSION['username']['user_id'],
							'created_datetime'  => date('d-m-Y'),
							'modified_by' 		=> $_SESSION['username']['user_id'],
							'modified_datetime'  => date('d-m-Y'),
						);
						$invoice_item_id = $this->Quotation_model->add_credit_item($item_data);
					}
				}
				$Return['result'] = $this->lang->line('xin_success_credit_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);

			exit;
		}
	}
	public function view_credit()
	{

		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('credit_id');

		$result = $this->Quotation_model->read_credit($id);

		$data = array(
			'credit_id ' => $result[0]->credit_id,
			'invoice_id' => $result[0]->invoice_id,
			'project_id' => $result[0]->project_id,
			'invoice_date' => $result[0]->invoice_date,
			'invoice_due_date' => $result[0]->invoice_due_date,
			'client_id' => $result[0]->client_id,
			'terms' => $result[0]->terms,
			'term_id' => $result[0]->term_id,
			'sub_total' => $result[0]->sub_total,
			'is_gst_inclusive' => $result[0]->is_gst_inclusive,
			'gst' => $result[0]->gst,
			'gst_value' => $result[0]->gst_value,
			'total' => $result[0]->total,
			'bill_status' => $result[0]->bill_status,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			'get_all_project' => $this->Xin_model->get_all_project(),
			'get_all_customer' => $this->Xin_model->all_customer(),
			'client_company_name' => $result[0]->client_company_name,
			'client_phone' => $result[0]->client_phone,
			'address' => $result[0]->address,
			'project_title' => $result[0]->project_title,
			'attn_name' => $result[0]->attn_name,
			'clientid' => $result[0]->client_id,
			'project_clientid' => $result[0]->project_clientid,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			// 'get_all_suppliers' => $this->Xin_model->all_suppliers(),
			// 'get_all_countries' => $this->Xin_model->get_countries(),
			// 'get_all_packings' => $this->Xin_model->get_packing_type()->result(),
			// 'get_payment_methods' => $this->Xin_model->get_payment_method()->result(),
			'get_all_items' => $this->Quotation_model->read_credit_item($result[0]->credit_id),
			'get_gst' => $this->Xin_model->get_gst(),
			'all_payment_terms' => $this->Xin_model->get_payment_term(),
			'quotation_result' => $this->Quotation_model->ajax_project_quotation_info($result[0]->project_id),
			'invoice_result' => $this->Quotation_model->ajax_invoice_info($result[0]->project_id)
		);
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($data);exit;
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/finance/dialog_view_credit', $data);
		} else {
			redirect('admin/');
		}
	}
	public function delete_credit()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Quotation_model->delete_credit($id);
		$this->Quotation_model->delete_credit_items($id);
		if (isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_credit_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
	public function invoice_pdf_create()
	{

		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);


		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();

		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['get_gst'] = $this->Xin_model->get_gst();

		$result = $this->Quotation_model->read_invoice($id);
		// print_r($result);exit;
		$inv_logo = $this->db->select('invoice_logo')->get('xin_system_setting')->result();
		// Read the image file into a variable and encode it as base64
		$image_path = base_url() . 'uploads/logo/Orion_Invoice_Logo.png'; // Replace with the actual image path
		// $image_path = base_url() . 'uploads/logo/' . $inv_logo[0]->invoice_logo; // Replace with the actual image path
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
		$query1 = $this->db->get('xin_system_setting')->result();

		// Access the 'logo1' property from the query result
		$logoUrl = base_url('uploads/quo/' . $query[0]->logo1);
		$logoUrl2 = base_url('uploads/quo/' . $query[0]->logo2);
		$logoUrl3 = base_url('uploads/quo/' . $query[0]->logo3);
		$head_logo = base_url('uploads/logo/' . $query1[0]->invoice_logo);
		$head_address_logo = base_url('uploads/logo/' . $query1[0]->invoice_address_logo);


		$image_data = file_get_contents($logoUrl, false, $context);
		$image_data2 = file_get_contents($logoUrl2, false, $context);
		$image_data3 = file_get_contents($logoUrl3, false, $context);
		$inv_head_logo = file_get_contents($head_logo, false, $context);
		// $inv_head_addr_logo = file_get_contents($head_address_logo, false, $context);


		$logoUrl = base64_encode($image_data);
		$logoUrl2 = base64_encode($image_data2);
		$logoUrl3 = base64_encode($image_data3);
		$logoUrl4 = base64_encode($inv_head_logo);
		$logoUrl5 = $query1[0]->invoice_address_logo;

		// echo "<pre>";print_r($result);exit;
		$estId = $result[0]->bill_estimateid;
		$data = array(
			'breadcrumbs' => 'View Invoice',
			'path_url' => 'invoice',
			'invoice_id ' => $result[0]->invoice_id,
			'invoice_no' => $result[0]->invoice_no,
			'project_id' => $result[0]->project_id,
			'invoice_date' => $result[0]->invoice_date,
			'tasks' => $result[0]->task_title,
			'invoice_due_date' => $result[0]->invoice_due_date,
			'client_id' => $result[0]->client_id,
			'm_do_no' => $result[0]->m_do_no,
			'm_order_no' => $result[0]->m_order_no,
			'terms' => $result[0]->terms1,
			'cterm' => $result[0]->cterm,
			'sub_total1' => $result[0]->sub_total,
			'is_gst_inclusive' => $result[0]->is_gst_inclusive,
			'gst' => $result[0]->gst,
			'gst_value' => $result[0]->gst_value,
			'total1' => $result[0]->total,
			'bill_status' => $result[0]->bill_status,
			'pic_name' =>  $result[0]->pic_name,
			'pic_email' =>  $result[0]->pic_email,
			'pic_contact' =>  $result[0]->pic_contact,
			'bill_final_amount' =>  $result[0]->bill_final_amount,
			'bill_estimateid' =>  $result[0]->bill_estimateid,
			'client_company_name' => $result[0]->client_company_name,
			'f_name' => $result[0]->f_name,
			'u_email' => $result[0]->u_email,
			'client_phone' => $result[0]->client_phone,
			'address' => $result[0]->address,
			'project_title' => $result[0]->project_title,
			'attn_name' => $result[0]->attn_name,
			'clientid' => $result[0]->client_id,
			'project_clientid' => $result[0]->project_clientid,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			'get_all_project' => $this->Xin_model->get_all_project(),
			'get_all_customer' => $this->Xin_model->all_customer(),
			'settings' => $this->Xin_model->read_company_setting_info(1),
			// 'get_all_suppliers' => $this->Xin_model->all_suppliers(),
			// 'get_all_countries' => $this->Xin_model->get_countries(),
			// 'get_all_packings' => $this->Xin_model->get_packing_type()->result(),
			// 'get_payment_methods' => $this->Xin_model->get_payment_method()->result(),
			// 'get_all_items' => $this->Quotation_model->get_invoice_items($result[0]->invoice_id),
			'get_all_items' => $this->Quotation_model->get_invoice_items1($result[0]->invoice_id),
			'get_gst' => $this->Xin_model->get_gst(),
			'all_payment_terms' => $this->Xin_model->get_payment_term(),
			'quotation_result' => $this->Quotation_model->ajax_project_quotation_info($result[0]->project_id),
			'invoice_result' => $this->Quotation_model->ajax_invoice_info($result[0]->project_id),
			// 'lineitems'  => $this->Quotation_model->lineitem_estimateid($estId),
			'image_base64' => $image_base64,
			'logoUrl' => $logoUrl,
			'logoUrl2' => $logoUrl2,
			'logoUrl3' => $logoUrl3,
			'logoUrl4' => $logoUrl4,
			'logoUrl5' => $logoUrl5,
		);
		// echo "<pre>";
		// print_r($data);exit;			
		$html = $this->load->view('admin/finance/get_invoice_receipt', $data, true);
		// Create Dompdf instance with options
		$options = new Options();
		// $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parser
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isPhpEnabled', true);
		$options->set('isRemoteEnabled', true);
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

		$dompdf->stream($result[0]->invoice_no . ".pdf", ['Attachment' => false]);
	}

	public function view_invoice()
	{
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);


		$session = $this->session->userdata('username');
		$data['title'] = 'Invoice | ' . $this->Xin_model->site_title();

		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['all_suppliers'] = $this->Xin_model->all_suppliers();

		$data['all_countries'] = $this->Xin_model->get_countries();
		$data['get_gst'] = $this->Xin_model->get_gst();

		$result = $this->Quotation_model->read_invoice($id);
		// echo "<pre>";print_r($result);exit;
		$estId = $result[0]->bill_estimateid;
		$data = array(
			'title' => 'View Invoice | ' . $this->Xin_model->site_title(),
			'breadcrumbs' => 'View Invoice',
			'path_url' => 'invoice',
			'invoice_id ' => $result[0]->invoice_id,
			'invoice_no' => $result[0]->invoice_no,
			'tasks' => $result[0]->task_title,
			'project_id' => $result[0]->project_id,
			'invoice_date' => date('d/m/Y', strtotime($result[0]->invoice_date)),
			'invoice_due_date' => $result[0]->invoice_due_date,
			'client_id' => $result[0]->cid,
			'm_do_no' => $result[0]->m_do_no,
			'm_order_no' => $result[0]->m_order_no,
			'terms' => $result[0]->terms1,
			'cterm' => $result[0]->cterm,
			'sub_total1' => $result[0]->sub_total,
			'is_gst_inclusive' => $result[0]->is_gst_inclusive,
			'gst' => $result[0]->gst,
			'gst_value' => $result[0]->gst_value,
			'total1' => $result[0]->total,
			'bill_status' => $result[0]->bill_status,
			'pic_name' =>  $result[0]->pic_name,
			'pic_email' =>  $result[0]->pic_email,
			'pic_contact' =>  $result[0]->pic_contact,
			'bill_final_amount' =>  $result[0]->bill_final_amount,
			'bill_estimateid' =>  $result[0]->bill_estimateid,
			'client_company_name' => $result[0]->client_company_name,
			'f_name' => $result[0]->f_name,
			'u_email' => $result[0]->u_email,
			'client_phone' => $result[0]->client_phone,
			'address' => $result[0]->address,
			'project_title' => $result[0]->project_title,
			'attn_name' => $result[0]->attn_name,
			'clientid' => $result[0]->client_id,
			'project_clientid' => $result[0]->project_clientid,
			'created_datetime' => $result[0]->created_datetime,
			'created_by' => $result[0]->created_by,
			'get_all_project' => $this->Xin_model->get_all_project(),
			'get_all_customer' => $this->Xin_model->all_customer(),
			'settings' => $this->Xin_model->read_company_setting_info(1),
			'invoice_settings' => $this->db->get('xin_system_setting')->result(),
			// 'get_all_suppliers' => $this->Xin_model->all_suppliers(),
			// 'get_all_countries' => $this->Xin_model->get_countries(),
			// 'get_all_packings' => $this->Xin_model->get_packing_type()->result(),
			// 'get_payment_methods' => $this->Xin_model->get_payment_method()->result(),
			'get_all_items' => $this->Quotation_model->get_invoice_items1($result[0]->invoice_id),
			'get_gst' => $this->Xin_model->get_gst(),
			'all_payment_terms' => $this->Xin_model->get_payment_term(),
			'quotation_result' => $this->Quotation_model->ajax_project_quotation_info($result[0]->project_id),
			'invoice_result' => $this->Quotation_model->ajax_invoice_info($result[0]->project_id),
			'lineitems'  => $this->Quotation_model->lineitem_estimateid($estId)
		);
		// echo "<pre>";
		// print_r($data);exit;			

		$data['subview'] = $this->load->view('admin/finance/view_invoice', $data, TRUE);
		$this->load->view('admin/layout/pms/layout_pms', $data); //page load
	}
	public function get_term_details()
	{

		$id = $this->uri->segment('4');
		$get_data = $this->Quotation_model->get_term_details($id);

		echo json_encode($get_data);
	}

	function get_prod_details($id)
	{


		$data['products'] = $this->db->where('product_id', $id)->get('product')->result();


		// Set the content type to JSON
		$this->output->set_content_type('application/json');

		// Encode the data as JSON and output it
		$this->output->set_output(json_encode($data));
	}
	public function related_data()
	{

		$id = $this->uri->segment('4');
		$data['records'] = $this->Quotation_model->get_invoice_items1($id);
		$data['client_project'] = $this->db->where('bill_estimateid', $id)->join('projects', 'estimates.bill_projectid=projects.project_id', 'left')->get('estimates')->result();
		$data['products'] = $this->db->get('product')->result();
		$sum = 0;
		$dat = array();
		foreach ($data['records'] as $v) {
			$dat[] = $v->amount;
		}
		$data['sum'] = array_sum($dat);

		// Set the content type to JSON
		$this->output->set_content_type('application/json');

		// Encode the data as JSON and output it
		$this->output->set_output(json_encode($data));
		// $data['records'] =  json_encode($get_data);

	}

	function get_client_address()
	{
		$id = $this->uri->segment(4);
		$data['billing_address'] = $this->db->where('client_id', $id)->get('billing_addresses')->result();
		$data['shipping_address'] = $this->db->where('client_id', $id)->get('shipping_addresses')->result();
		echo json_encode($data);
	}

	public function related_data_dialog()
	{

		$id = $this->uri->segment('4');
		// $data['records'] = $this->Quotation_model->get_lineitems_data($id);
		$data['records'] = $this->Quotation_model->get_invoice_items1($id);
		// print_r($data);exit;
		// $data['client_project'] = $this->db->where('bill_estimateid', $id)->get('estimates')->result();
		$data['products'] = $this->db->get('product')->result();
		// $sum = 0;
		// $dat = array();
		// foreach ($data['records'] as $v) {
		// 	$dat[] = $v->total;
		// }
		// $data['sum'] = array_sum($dat);

		// Set the content type to JSON
		$this->output->set_content_type('application/json');

		// Encode the data as JSON and output it
		$this->output->set_output(json_encode($data));
		// $data['records'] =  json_encode($get_data);

	}
	public function read_invoice()
	{
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('invoice_id');
		$resultd = $this->Quotation_model->read_invoice($id);
		$data['all_quotation'] = $this->Quotation_model->all_quotation_();
		// echo "<pre>";print_r($resultd);exit;


		$qtn_no = $resultd[0]->quotation_no;
		// echo "<pre>";print_r($data['all_quotation']);exit;
		// $data['all_projects'] = $this->Xin_model->get_all_project();

		// if ($resultd[0]->type == 'Manual') {
		// $lineitems = $this->Quotation_model->get_inv_products($resultd[0]->invoice_id);
		// $lineitems = $this->Quotation_model->get_invoice_items1($resultd[0]->invoice_id);
		if ($resultd[0]->invoice_id) {
			$lineitems = $this->Quotation_model->get_invoice_items1($resultd[0]->invoice_id);
		} else {
			$lineitems = $this->Quotation_model->get_invoice_items2();
		}

		// }

		$data = array(
			'get_gst' => $this->Xin_model->get_gst()->result(),
			'all_quotation' => $this->Quotation_model->all_quotation_(),
			'get_all_projects' =>  $this->Xin_model->get_all_project(),
			'mile_id' => $resultd[0]->mile_id,
			'task_id' => $resultd[0]->task_id,
			'all_customer'   => $this->Quotation_model->get_crm_customer(),
			'all_payment_terms' => $this->Xin_model->get_payment_term(),
			'estimate_id' => $this->Quotation_model->estimate_($qtn_no),
			'm_do_no' => ($resultd[0]->m_do_no) ?? $lineitems[0]->m_do_no,
			'm_order_no' => $resultd[0]->m_order_no,
			"invoice_id" => ($resultd[0]->invoice_id) ?? 0,
			"invoice_no" => $resultd[0]->invoice_no,
			"quotation_no" => ($resultd[0]->qno) ?? '',
			'billing_addresses' => $resultd[0]->billing_addresses,
			'shipping_addresses' => $resultd[0]->shipping_addresses,
			"cond_term" => $resultd[0]->cterm,
			"letter_acceptance_no" => $resultd[0]->letter_acceptance_no,
			"project_id" => ($resultd[0]->project_id) ?? $lineitems[0]->project_id,
			"client_id" => ($resultd[0]->client_id) ?? $lineitems[0]->client_id,
			"terms" => $resultd[0]->terms,
			"invoice_date" => $resultd[0]->invoice_date,
			"invoice_due_date" => $resultd[0]->invoice_due_date,
			"sub_total" => $resultd[0]->sub_total,
			"gst" => $resultd[0]->gst,
			"gst_value" => $resultd[0]->gst_value,
			"gst_inclusive" => $resultd[0]->is_gst_inclusive,
			"total" => $resultd[0]->total,
			"bill_status" => $resultd[0]->bill_status,
			"status" => $resultd[0]->status,
			"modified_datetime" => $resultd[0]->modified_datetime,
			"pay_receive_at" => $resultd[0]->pay_receive_at,
			"all_items" => $this->db->get('product')->result(),
			// "type" => $resultd[0]->type
			"type" => "Manual"

		);
		//echo $this->db->last_query();exit;
		// echo "<pre>";print_r($data);exit;
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/finance/dialog_invoice', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update_invoice()
	{
		$id = $this->input->post('invoice_id1');
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		// echo "<pre>";
		// print_r($_POST);
		if ($Return['error'] != '') {
			$this->output($Return);
			exit;
		}

		$ids = $this->input->post('estimate_id1');
		$estimateData = $this->Quotation_model->get_qoutation_no($ids);

		$data = array(
			'invoice_id' => $this->input->post('invoice_id1'),
			'quotation_no' => !empty($estimateData[0]->quotation_no) ? $estimateData[0]->quotation_no :(!empty($this->input->post('qt_no1')) ? $this->input->post('qt_no1') : ''),

			'm_do_no' => $this->input->post('m_do_no1'),
			'm_order_no' => $this->input->post('m_order_no1'),
			'billing_addresses' => $this->input->post('u_bill_address'),
			'shipping_addresses' => $this->input->post('u_delivery_address'),
			'cterm' => $this->input->post('cterm6'),
			'client_id' => $this->input->post('client_id1'),
			'project_id' => $this->input->post('project_id1'),
			'invoice_date' => $this->input->post('invoice_date1'),
			'terms' => $this->input->post('payment_term1'),
			'sub_total' => $this->input->post('sub_t1'),
			'is_gst_inclusive' => $this->input->post('inclusive_gst2'),
			'gst' => ($this->input->post('inclusive_gst2') == 'on') ? 9 : $this->input->post('order_gst3'),
			'gst_value' => ($this->input->post('inclusive_gst2') == 'on') ? $this->input->post('d_gst_i1') : $this->input->post('g_val1'),
			'total' => ($this->input->post('inclusive_gst2') == 'on') ? $this->input->post('sub_t1') : $this->input->post('sub_t1') + $this->input->post('g_val1'),
			'bill_status' => $this->input->post('bill_status'),
			'modified_by' => $_SESSION['username']['user_id'],
			'modified_datetime' => date('Y-m-d h:i:s'),
		);

		$result = $this->Quotation_model->update_invoice($data, $id);

		if ($result) {
			// Prepare and update items
			$types = $this->input->post('type1');
			$u_items = $this->input->post('u_item1');
			$descriptions = $this->input->post('item_description1');
			$units = $this->input->post('unit1');
			$rates = $this->input->post('rate1');
			$quantities = $this->input->post('quantity1');
			$totals = $this->input->post('total1');

			// Ensure all arrays have the same length
			if (count($types) > 0) {
				// Delete existing items to prevent duplicates
				$this->db->delete('finance_invoice_description_mapping', ['invoice_id' => $id]);

				// Insert updated items
				for ($i = 0; $i < count($types); $i++) {
					// Ensure all required fields are set
					if (isset($types[$i], $rates[$i], $quantities[$i], $totals[$i])) {
						$invoice_item = [
							'invoice_id' => $id,
							'item' => $u_items[$i] ?? '',
							'job_description' => ($types[$i] == 'plain') ? ($descriptions[$i] ?? '') : '',
							'unit' => $units[$i],
							'rate' => $rates[$i],
							'item_qtn' => $quantities[$i],
							'item_type' => $types[$i],
							'total' => $totals[$i],
							'created_by' => $_SESSION['username']['user_id'],
							'created_datetime' => date('Y-m-d h:i:s'),
						];

						// Add the item back into the table
						$this->Quotation_model->add_invoice_item($invoice_item);
					}
				}
			}


			$Return['result'] = $this->lang->line('xin_success_invoice_updated');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}

		$this->output($Return);
		exit;
	}


	public function delete_invoice()
	{
		$session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Quotation_model->delete_invoice($id);
		$this->Quotation_model->delete_invoice_items($id);


		if (isset($id)) {
			$Return['result'] = $this->lang->line('xin_success_invoice_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
	}
	function getProducts()
	{
		$res = $this->db->get('product')->result();
		echo json_encode($res);
	}

	function getInvDetails()
	{
		$id = $this->uri->segment(4);


		// $res = $this->Quotation_model->get_lineitems_data($id);
		$res = 	$this->db->select('*,finance_invoice.total as sum')
			->from('finance_invoice')
			->join('finance_invoice_description_mapping', 'finance_invoice.invoice_id = finance_invoice_description_mapping.invoice_id', 'left')
			->join('product', 'finance_invoice_description_mapping.item = product.product_id', 'left')
			->where('finance_invoice.invoice_id', $id)
			->or_where('finance_invoice_description_mapping.item REGEXP "^[0-9]+$"', 'left')

			->get()->result();
		echo json_encode($res);
	}
}
