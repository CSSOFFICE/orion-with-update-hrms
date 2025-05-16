<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Category extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		//load the model
		$this->load->model("category_model");
		$this->load->model("Xin_model");
	}
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
    public function index(){
        $session = $this->session->userdata('username');
		if (empty($session)) {
			redirect('admin/');
		}
		$system = $this->Xin_model->read_setting_info(1);
		if ($system[0]->module_awards != 'true') {
			redirect('admin/dashboard');
		}
		$data['title'] = $this->lang->line('xin_category') . ' | ' . $this->Xin_model->site_title();
		$data['breadcrumbs'] = $this->lang->line('xin_category');
		$data['path_url'] = 'category';
		

		$role_resources_ids = $this->Xin_model->user_role_resource();
		if (in_array('1705', $role_resources_ids)) {
			if (!empty($session)) {

				$data['subview'] = $this->load->view("admin/category/category_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
    }
	public function category_list(){
		$data['title'] = $this->Xin_model->site_title();
		$session = $this->session->userdata('username');
		
		if (!empty($session)) {
			$this->load->view("admin/category/category_list", $data);
		} else {
			redirect('admin/');
		}
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		//get_company_awards
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$get_category = $this->category_model->get_category();

		$data = array();
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$i = 0;
		foreach ($get_category->result() as $r) {
			$i++;
			$emp_name=$this->db->select('first_name,last_name')->from('xin_employees')->where('user_id',$r->created_by)->get()->result();
			
			//edit
			if (in_array('1703', $role_resources_ids)) {
			$edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-category_id="' . $r->category_id . '"><span class="fa fa-pencil"></span></button></span>';
			}else{
				$edit='';
			}

			if (in_array('1704', $role_resources_ids)) {
			$delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->category_id . '"><span class="fa fa-trash"></span></button></span>';
			}else{
				$delete='';
			}


			$combhr = $edit . $delete;

			$data[] = array(
				
				$combhr,
				
				$r->category,
				$emp_name[0]->first_name ." ".$emp_name[0]->last_name,
				date('d-M-Y h:i',strtotime($r->created_at))

			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => $get_category->num_rows(),
			"recordsFiltered" => $get_category->num_rows(),
			"data" => $data
		);
		echo json_encode($output);
		exit();
	}
	public function add_category(){
		if ($this->input->post('add_type') == 'category') {
			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('category_name') == '') {
				$Return['error'] = $this->lang->line('error_category_name_required_field');
			} 

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}
			
			$data = array(
				// 'supplier_id' =>$this->input->post('supplier_name'),
				'category' => $this->input->post('category_name'),
				'created_by' => $_SESSION['username']['user_id'],
				'created_at' => date('Y-m-d h:i:s'),
			);
			
			

			$result = $this->category_model->add_category($data);
			if ($result) {
				$Return['result'] = $this->lang->line('xin_success_category_added');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
	}
	public function read(){
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('category_id');

		$result = $this->category_model->read_category($id);
		$data = array(
			'category_id' => $result[0]->category_id,
			'category_name' => $result[0]->category,
			
		);
		$session = $this->session->userdata('username');
		if (!empty($session)) {
			$this->load->view('admin/category/dialog_category', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update()
	{

		if ($this->input->post('edit_type') == 'category') {
			$id = $_POST['category_id'];

			/* Define return | here result is used to return user data and error for error message */
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
			$Return['csrf_hash'] = $this->security->get_csrf_hash();

			if ($this->input->post('category_name') == '') {
				$Return['error'] = $this->lang->line('error_category_name_required_field');
			} 

			if ($Return['error'] != '') {
				$this->output($Return);
				exit;
			}

			$data = array(
				// 'supplier_id' =>$this->input->post('supplier_name'),
				'category' => $this->input->post('category_name'),
				'modified_by' => $_SESSION['username']['user_id'],
				'modified_at' => date('Y-m-d h:i:s'),
			);

			//  $this->product_model->update($data,$id);
			$result = $this->category_model->update($data,$id);

			if ($result) {
				$Return['result'] = $this->lang->line('xin_success_category_updated');
			} else {
				$Return['error'] = $this->lang->line('xin_error_msg');
			}
			$this->output($Return);
			exit;
		}
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
		$result = $this->category_model->delete_record($id);
		if ($result) {
			$Return['result'] = $this->lang->line('xin_success_category_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
	}
}