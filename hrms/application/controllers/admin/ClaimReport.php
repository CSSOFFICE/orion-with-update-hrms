<?php
class ClaimReport extends My_Controller{
    public function __construct() {
        parent::__construct();
		$this->load->model("Xin_model");
		$this->load->model("Custom_fields_model");
		$this->load->model("Project_model");
		$this->load->model("Claim_model");
		$this->load->model("Receivable_model");


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
	


        $data['breadcrumbs'] = 'GST Claim Report';
		$data['path_url'] = 'claimreport';
		$role_resources_ids = $this->Xin_model->user_role_resource();
       
        if(in_array('3301',$role_resources_ids)) {
			if(!empty($session)){ 
				$data['subview'] = $this->load->view("admin/reports/claim_report_list", $data, TRUE);
				$this->load->view('admin/layout/pms/layout_pms', $data); //page load
			} else {
				redirect('admin/');
			}
		} else {
			redirect('admin/dashboard');
		}
    }
	public function claim_report_list(){
		
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		
		$start_date = $_GET['start_date'];
		$end_date = $_GET['end_date'];

		//$role_resources_ids = $this->Xin_model->user_role_resource();
		if(!empty($session)){ 
			$this->load->view("admin/reports/claim_report_list", $data);
		} else {
			redirect('admin/');
		}
		$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			
			$get_claims = $this->Claim_model->get_receivable_list($start_date,$end_date);
			
			$get_credits = $this->Claim_model->get_credit_list($start_date,$end_date);
			//echo $this->db->last_query();exit;
			$data = array();

			foreach($get_claims->result() as $r) {
					
				
		// $edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-claim_id="'. $r->claim_id  . '"><span class="fa fa-pencil"></span></button></span>';
		// $delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->claim_id  . '"><span class="fa fa-trash"></span></button></span>';			
		//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/purchase/pdf_create/'.$r->purchase_order_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
		//$combhr = $download.$edit.$delete;
			//$combhr = $edit.$delete;
				
			$data[] = array(
				$r->yearmonth,
				$r->sales,
				$r->gst_value,
				$r->total,
				$r->gst
					
			);
		}
		$i=1;
		foreach($get_credits->result() as $r) {
					
				
			// $edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-claim_id="'. $r->claim_id  . '"><span class="fa fa-pencil"></span></button></span>';
			// $delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->claim_id  . '"><span class="fa fa-trash"></span></button></span>';			
			//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/purchase/pdf_create/'.$r->purchase_order_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
			//$combhr = $download.$edit.$delete;
				//$combhr = $edit.$delete;
					
				$data[] = array(
					'Credit Note'.$i,
					$r->sales,
					$r->gst_value,
					$r->total,
					$r->gst
						
				);
				$i++;
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
	public function claim_payable_report_list(){
		$session = $this->session->userdata('username');
		if(empty($session)){ 
			redirect('admin/');
		}
		$data['title'] = $this->Xin_model->site_title();
		$data['all_projects'] = $this->Xin_model->get_all_project();
		
		$start_date = $_GET['start_date'];
		$end_date = $_GET['end_date'];
		//$role_resources_ids = $this->Xin_model->user_role_resource();
		if(!empty($session)){ 
			$this->load->view("admin/reports/claim_report_list", $data);
		} else {
			redirect('admin/');
		}
		$draw = intval($this->input->get("draw"));
			$start = intval($this->input->get("start"));
			$length = intval($this->input->get("length"));
			
			
			$get_claims = $this->Claim_model->get_payable_list($start_date,$end_date);
			//echo $this->db->last_query();exit;
			$data = array();

			foreach($get_claims->result() as $r) {
					
				//$edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-payable_id="'. $r->payable_id  . '"><span class="fa fa-pencil"></span></button></span>';
		// $edit = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_edit').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-claim_id="'. $r->claim_id  . '"><span class="fa fa-pencil"></span></button></span>';
		// $delete = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_delete').'"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="'. $r->claim_id  . '"><span class="fa fa-trash"></span></button></span>';			
		//$download = '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_download').'"><a href="'.site_url().'admin/purchase/pdf_create/'.$r->purchase_order_id.'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';
		//$combhr = $download.$edit.$delete;
			//$combhr = $edit.$delete;
				
			$data[] = array(
				$r->yearmonth,
				$r->purchase,
				$r->gst_value,
				$r->total,
				$r->gst
					
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
	public function pdf_create(){
			$get_claims = $this->Claim_model->get_receivable_list();
			$get_credits = $this->Claim_model->get_credit_list();		
			$get_payable_claims = $this->Claim_model->get_payable_list();

             header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename=\"test".".csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");

            $file = fopen('php://output', 'w');
			$columns[]="KREATIVE BUILDER PTE LTD";
			fputcsv($file, $columns);
		   
			$row['title'] ="";
			fputcsv($file, array( $row['title'] ));

			$row['title'] ="Working for GST return for the period of 01/06/22 to 31/08/22";
			fputcsv($file, array( $row['title'] ));
			
			$row['title'] ="";
			fputcsv($file, array( $row['title'] ));

			$row['title'] ="Taxable Supplies";
			fputcsv($file, array( $row['title'] ));
  
			$row['title']="";
			$row['title1']="Sales";
			$row['title3'] ="GST";
			$row['title2'] ="Total";
			$row['title4'] ="GST(%)";
			

			fputcsv($file, array($row['title'],$row['title1'], $row['title3'],$row['title2'],$row['title4']));
			$total_gst=$total_amount=$total_sales=0;
			foreach ($get_claims->result() as $claim) {
				
				$row['claim_month']  = $claim->yearmonth;
				$row['sales']    = $claim->sales;
				$row['gst_value']    = $claim->gst_value;
				$row['total']    = $claim->total;
				$row['gst']    = $claim->gst.'%';
				$total_gst += $claim->gst_value;
				$total_amount += $claim->total;
				$total_sales += $claim->sales;

				fputcsv($file, array($row['claim_month'],$row['sales'],$row['gst_value'] ,$row['total'],$row['gst']));
				
			}
			$i=1;
			$c_total_gst=$c_total_amount=$c_total_sales=0;

			foreach ($get_credits->result() as $credit) {
				
				$row['claim_month']  = "Credit Note".$i;
				$row['sales']    = $credit->sales;
				$row['gst_value']    = $credit->gst_value;
				$row['total']    = $credit->total;
				$row['gst']    = $credit->gst.'%';
				$c_total_gst += $credit->gst_value;
				$c_total_amount += $credit->total;
				$c_total_sales += $credit->sales;

				fputcsv($file, array($row['claim_month'],$row['sales'],$row['gst_value'] ,$row['total'],$row['gst']));
				$i++;
			}
			
			$row['title'] ="";
			fputcsv($file, array( $row['title'] ));

			$row['claim_month']  = "A";
			$row['sales']    = $total_sales - $c_total_sales;
			$row['gst_value']    = $total_gst - $c_total_gst;
			$row['total']    = $total_amount - $c_total_amount;
			fputcsv($file, array($row['claim_month'],$row['sales'],$row['gst_value'] ,$row['total']));
			
			$row['title'] ="";
			fputcsv($file, array( $row['title'] ));
			$row['title']="";
			$row['title1']="Purcahse";
			$row['title3'] ="GST";
			$row['title2'] ="Total";
			$row['title4'] ="GST(%)";
			

			fputcsv($file, array($row['title'],$row['title1'], $row['title3'],$row['title2'],$row['title4']));
			$p_total_gst=$p_total_amount=$p_total_sales=0;
			foreach ($get_payable_claims->result() as $claim) {
				
				$row['claim_month']  = $claim->yearmonth;
				$row['sales']    = $claim->purchase;
				$row['gst_value']    = $claim->gst_value;
				$row['total']    = $claim->total;
				$row['gst']    = $claim->gst.'%';
				$p_total_gst += $claim->gst_value;
				$p_total_amount += $claim->total;
				$p_total_sales += $claim->purchase;

				fputcsv($file, array($row['claim_month'],$row['sales'],$row['gst_value'] ,$row['total'],$row['gst']));
				
			}
			$row['claim_month']  = "B";
			$row['sales']    = $p_total_sales;
			$row['gst_value']    = $p_total_gst;
			$row['total']    = $p_total_amount;
			fputcsv($file, array($row['claim_month'],$row['sales'],$row['gst_value'] ,$row['total']));
			
			$row['title'] ="";
			fputcsv($file, array( $row['title'] ));
			$row['title1'] ="Tax Payable";
			
			$row['sales1']    = ($total_sales - $c_total_sales)-$p_total_sales;
			$row['gst_value1']    = ($total_gst - $c_total_gst)-$p_total_gst;
			$row['total1']    = ($total_amount - $c_total_amount)-$p_total_amount;
			fputcsv($file, array($row['title1'],$row['sales1'],$row['gst_value1'] ,$row['total1']));
			$row['title'] ="";
			fputcsv($file, array( $row['title'] ));

			$row['title'] ="TOTAL TAX PAYABLE/CLAIMABLE(GST:A-B)";
			
			$row['sales']    = '';
			$row['gst_value']    = ($total_gst - $c_total_gst)-$p_total_gst;
			$row['total']    = '';
			fputcsv($file, array($row['title'],$row['sales'],$row['gst_value'] ,$row['total']));
                fclose($file);
            exit;

	}
}