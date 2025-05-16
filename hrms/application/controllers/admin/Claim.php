<?php
class Claim extends My_Controller{

    public function __construct() {
        parent::__construct();
		$this->load->model("Xin_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Project_model");
		$this->load->model("Claim_model");

		
		



    }
    public function output($Return=array()){
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}
    public function index(){
		$session = $this->session->userdata('username');
		$data['title'] = $this->Xin_model->site_title();
		$data['get_all_projects'] = $this->Xin_model->get_all_project();
		$data['get_claims'] = $this->Claim_model->claim_list();
	
        $data['breadcrumbs'] = 'Claim';
		$data['path_url'] = 'claim';
		$data['get_gst'] = $this->Xin_model->get_gst();

		$role_resources_ids = $this->Xin_model->user_role_resource();
       
        if(in_array('3301',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/finance/claim_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
    }
	public function claim_list(){
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		$data['get_gst'] = $this->Xin_model->get_gst();
		
	
		$role_resources_ids = $this->Xin_model->user_role_resource();
		if(!empty($session)){ 
			$this->load->view("admin/finance/claim_list", $data);
		} else {
			redirect('admin/');
		}
		$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			
			$get_claims = $this->Claim_model->claim_list();
			//echo $this->db->last_query();exit;
			$data = array();

			foreach($get_claims->result() as $r) {
					
				//$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-payable_id="'. $r->payable_id  . '"><span class="fa fa-pencil"></span></button></span>';
		$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-claim_id="'. $r->claim_id  . '"><span class="fa fa-pencil"></span></button></span>';
		$delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->claim_id  . '"><span class="fa fa-trash"></span></button></span>';			
		//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/purchase/pdf_create/'.$r->purchase_order_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
		//$combhr = $download.$edit.$delete;
			$combhr = $edit.$delete;
				
			$data[] = array(
				$combhr,
				$r->project_title,
				$r->claimer_name,
				$r->amount,
				$r->created_datetime
				
					
			);
		}
        $output = array(
			"draw" => $draw,
			  "recordsTotal" => $get_claims->num_rows(),
			  "recordsFiltered" => $get_claims->num_rows(),
			  "data" => $data
		 );
	   echo json_encode($output);
	   exit();
	}
	public function add_claim(){
		$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();

		if ($this->input->post('claimer_name') === '') {
			$Return['error'] = $this->lang->line('xin_error_claimer_name');
		} 
		if ($Return['error'] != '') {
			$this->output($Return);
		}
		if(is_uploaded_file($_FILES['document_file']['tmp_name'])) {
			//checking image type
			$allowed =  array('png','jpg','jpeg','pdf','gif');
			$filename = $_FILES['document_file']['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(in_array($ext,$allowed)){
				$tmp_name = $_FILES["document_file"]["tmp_name"];
				$profile = "uploads/claims/";
				$set_img = base_url()."uploads/claims/";
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($_FILES["document_file"]["name"]);
				$newfilename = 'payment_'.round(microtime(true)).'.'.$ext;
				move_uploaded_file($tmp_name, $profile.$newfilename);
				$fname = $newfilename;			
			} else {
				$Return['error'] = $this->lang->line('xin_error_attatchment_type');
			}
		}else{
			$fname='';
		}
		$data = array(
			
			'project_id' 				=> $this->input->post('project_id'),
			'claimer_name'				=>$this->input->post('claimer_name'),
			'amount' 					=> $this->input->post('amount'),
			'is_gst_inclusive'  		=> $this->input->post('is_gst'),
			'gst' 						=> $this->input->post('gst'),
			'gst_value' 				=> floatval($this->input->post('amount'))*(floatval($this->input->post('gst'))/100),
			'total_amount'              => $this->input->post('total_amount'),
			'description' 				=> $this->input->post('description'),
			'file'				        =>$fname,
			'created_by'				=> $_SESSION['username']['user_id'],
			'created_datetime'			=> date('d-m-Y h:i:s')

			
		);
		
		$result = $this->Claim_model->add($data);
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_claim_added');
		
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
	}
	public function read(){
		$data['title'] = $this->Xin_model->site_title();
		$id = $this->input->get('claim_id');
		$result = $this->Claim_model->read_claim($id)->result();
		
		$data = array(
				'claim_id ' => $result[0]->claim_id,
				'claimer_name'=>$result[0]->claimer_name,
				'project_id' => $result[0]->project_id,
				'amount' => $result[0]->amount,
				'is_gst_inclusive' => $result[0]->is_gst_inclusive,
				'gst' => $result[0]->gst,
				'gst_value' => $result[0]->gst_value,
				'total_amount' => $result[0]->total_amount,
				'description' => $result[0]->description,
				'attachment' => $result[0]->file,
				'get_all_projects'=>$this->Xin_model->get_all_project(),
				'get_gst' => $this->Xin_model->get_gst()
				);
			
		$session = $this->session->userdata('username');
		if(!empty($session)){ 
			$this->load->view('admin/finance/dialog_claim', $data);
		} else {
			redirect('admin/');
		}
	}
	public function update(){
		
		if($this->input->post('edit_type')=='claim'){
			$Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
		$Return['csrf_hash'] = $this->security->get_csrf_hash();

		if ($this->input->post('claimer_name1') === '') {
			$Return['error'] = $this->lang->line('xin_error_claimer_name');
		} 
		if ($Return['error'] != '') {
			$this->output($Return);
		}
			if(is_uploaded_file($_FILES['document_file1']['tmp_name'])) {
				//checking image type
				$allowed =  array('png','jpg','jpeg','pdf','gif');
				$filename = $_FILES['document_file1']['name'];
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				
				if(in_array($ext,$allowed)){
					$tmp_name = $_FILES["document_file1"]["tmp_name"];
					$profile = "uploads/claims/";
					$set_img = base_url()."uploads/claims/";
					// basename() may prevent filesystem traversal attacks;
					// further validation/sanitation of the filename may be appropriate
					$name = basename($_FILES["document_file1"]["name"]);
					$newfilename = 'payment_'.round(microtime(true)).'.'.$ext;
					move_uploaded_file($tmp_name, $profile.$newfilename);
					$fname = $newfilename;			
				} else {
					$Return['error'] = $this->lang->line('xin_error_attatchment_type');
				}
			}else{
				$fname='';
			}
		$id = $this->input->post('claim_id');
		$data = array(
			'project_id' 				=> $this->input->post('project_id1'),
			'claimer_name'				=>$this->input->post('claimer_name1'),
			'amount' 					=> $this->input->post('amount1'),
			'is_gst_inclusive'  		=> $this->input->post('is_gst1'),
			'gst' 						=> $this->input->post('u_gst'),
			'gst_value' 				=> $this->input->post('amount1')*($this->input->post('u_gst')/100),
			'total_amount'              => $this->input->post('total_amount1'),
			'description' 				=> $this->input->post('description1'),
			'file' 						=> $fname,
			'modified_by'				=> $_SESSION['username']['user_id'],
			'modified_datetime'			=> date('d-m-Y')

			
		);
		
		$result = $this->Claim_model->update($data,$id);
		
		if ($result == TRUE) {
			$Return['result'] = $this->lang->line('xin_claim_updated');
		
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
		exit;
	}
}
public function delete_claim(){
	$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		/* Define return | here result is used to return user data and error for error message */
		$Return = array('result'=>'', 'error'=>'', 'csrf_hash'=>'');
		$id = $this->uri->segment(4);
		$Return['csrf_hash'] = $this->security->get_csrf_hash();
		$result = $this->Claim_model->delete($id);
		

	
		if(isset($id)) {
			$Return['result'] = $this->lang->line('xin_claim_deleted');
		} else {
			$Return['error'] = $this->lang->line('xin_error_msg');
		}
		$this->output($Return);
}
}