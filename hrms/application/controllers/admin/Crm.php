<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crm extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        //load the model
        $this->load->model("product_model");
        $this->load->model("Xin_model");
        $this->load->model("crm_model");
        $this->load->model("Custom_fields_model");
		$this->load->model("Project_model");
		$this->load->model("Quotation_model");
		$this->load->model("Purchase_model");
		$this->load->model("Receivable_model");
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
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
        $data['title'] = "CRM" . ' | ' . $this->Xin_model->site_title();
        $data['breadcrumbs'] = "CRM";
        $data['path_url'] = 'crm';

        $data['get_all_projects'] = $this->Xin_model->get_all_project();
        $data['all_units'] = $this->Xin_model->get_unit();
        $data['get_gst'] = $this->Xin_model->get_gst();


        $role_resources_ids = $this->Xin_model->user_role_resource();


        if (in_array('3301', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/crm/crm_list", $data, TRUE);
                $this->load->view('admin/layout/pms/layout_pms', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    //////////////////////////////////////////COMPANY FUNCTIONS START///////////////////////////////////////////
    public function tabComUpdate()
    {
        $id = $this->input->post('crm_c_id');
        // $get_item=$this->db->where('crm_company_cust_item_map.crm_company_cust_id',$id)->get('crm_company_cust_item_map')->result();
        $data = array(
            'company_name' => $this->input->post('com_name'),
            'name' => $this->input->post('name'),
            'company_uen' => $this->input->post('c_uen'),
            'c_contact_number' => $this->input->post('c_number'),
            'c_email' => $this->input->post('cust_email'),
            'c_postal_code' => $this->input->post('pos_code'),
            'address' => $this->input->post('com_address'),
            'c_unit_number' => $this->input->post('un_num'),
            'c_credit_limit' => $this->input->post('cr_limit')
        );
        $res =  $this->db->update('crm_customer', $data, ['crm_id' => $id]);
        // if(){

        // }
        if ($res) {
            $Return['result'] = "Update Successfull";
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $Return['error'] = "Update Failed";
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function get_comitems($id)
    {
        $item = $this->db->where('crm_company_cust_item_map.crm_company_cust_id', $id)->get('crm_company_cust_item_map')->result();
        echo json_encode($item);
        exit;
    }
    public function view_com_profile($id)
    {
        $session = $this->session->userdata('username');

        $role_resources_ids  = $this->Xin_model->user_role_resource();

        // print_r($role_resources_ids);exit;           
        $get_com_cust = $this->db->where('crm_id',$id)->get('crm_customer')->result();
        // $this->crm_model->get_com_profile($id);

        // $get_item=$this->db->where('crm_company_cust_item_map.crm_company_cust_id',$id)->get('crm_company_cust_item_map')->result();

        // if($get_item > 0){
        //     $data=array(
        //         'title' => $this->Xin_model->site_title(),
        //         'breadcrumbs' => "Company Customer Profile",
        //         'path_url' => 'crm',
        //         'crm_c_id'=>$get_com_cust[0]->crm_c_id,
        //         'company_name'=>$get_com_cust[0]->company_name,
        //         'name'=>$get_com_cust[0]->name,
        //         'company_uen'=>$get_com_cust[0]->company_uen,
        //         'c_contact_number'=>$get_com_cust[0]->c_contact_number,
        //         'c_email'=>$get_com_cust[0]->c_email,
        //         'c_postal_code'=>$get_com_cust[0]->c_postal_code,
        //         'address'=>$get_com_cust[0]->address,
        //         'c_unit_number'=>$get_com_cust[0]->c_unit_number, 
        //         'c_credit_limit'=>$get_com_cust[0]->c_credit_limit, 
        //         'items_p_ic'=>$get_item[0]->p_ic,
        //         'items_c_n'=>$get_item[0]->c_n,
        //         'items_e_mail'=>$get_item[0]->e_mail,
        //         'items_a_dd'=>$get_item[0]->a_dd,
        //     );  
        // }else{
            // print_r($get_com_cust);exit;
        $data = array(
            'title' => $this->Xin_model->site_title(),
            'breadcrumbs' => "Company Customer Profile",
            'path_url' => 'crm',
            'crm_id' => $get_com_cust[0]->crm_id,
            'company_name' => $get_com_cust[0]->company_name,
            'name' => $get_com_cust[0]->name,
            'company_uen' => $get_com_cust[0]->company_uen,
            'c_contact_number' => $get_com_cust[0]->c_contact_number,
            'c_email' => $get_com_cust[0]->c_email,
            'c_postal_code' => $get_com_cust[0]->c_postal_code,
            'address' => $get_com_cust[0]->address,
            'c_unit_number' => $get_com_cust[0]->c_unit_number,
            'c_credit_limit' => $get_com_cust[0]->c_credit_limit,
            // 'get_all_proj' => $this->crm_model->get_all_crm_com_proj($id),
            'get_term_condition' => $this->Xin_model->get_term_condition()->result(),
            'all_units' => $this->Xin_model->get_unit()->result(),
            'all_projects'=>$this->Xin_model->get_all_project(),
            'all_suppliers'=> $this->Xin_model->all_suppliers(),
            'all_payment_terms'=>$this->Xin_model->get_payment_term()->result(),
            'all_shipping_terms'=>$this->db->get('xin_shipping_term')->result(),
            'get_gst'=> $this->Xin_model->get_gst()->result(),
           

        );
        // }



        if (in_array('3301', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view('admin/crm/dialog_com_profile_read', $data, TRUE);
                $this->load->view('admin/layout/pms/layout_pms', $data); //page load

            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }
    public function project_list_crm_com($id)
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        $data['get_proj'] = $this->crm_model->get_crm_com_proj($id);

        if (!empty($session)) {
            $this->load->view("admin/crm/dialog_com_profile_read", $data, TRUE);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Xin_model->user_role_resource();
        $get_proj = $this->crm_model->get_crm_com_proj($id);

        $data = array();

        $i=0; foreach ($get_proj as $r) {$i++;
            // $profile='<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><a href="' . site_url() . 'admin/crm/view_profile/' . $r->crm_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
            $edit =     '<a class="btn btn-outline-success btn-circle btn-md"  data-toggle="modal" data-target=".edit-modal-data"  data-crm_proj_id="' . $r->crm_com_proj_id  . '"><i class="sl-icon-note"></i></a>';
            $delete =   '<a class="btn btn-outline-danger btn-circle btn-md com-project-delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->crm_com_proj_id  . '"><i class="sl-icon-trash"></i></a>';
            // $profile =  '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-crm_id="'. $r->crm_id  . '"><span class="fa fa-eye"></span></button></span>';

            $combhr = $edit . $delete;
            // $combhr =$profile. $edit . $delete;

            $data[] = array(
                $i,
                $r->crm_com_proj_title,
                $r->crm_com_proj_start,
                $r->crm_com_proj_stop,
                $r->crm_com_proj_des,
                $combhr, 
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($get_proj),
            "recordsFiltered" => count($get_proj),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }
    public function add_crm_com_proj()
    {
        $session = $this->session->userdata('username');
        if ($this->input->post('add_type') == 'crm_com_proj') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();


            if ($this->input->post('proj_title') == '') {
                $Return['error'] = 'Project Title Required';
            } else if ($this->input->post('proj_s_date') == '') {
                $Return['error'] = 'Project Start Date Required';
            } else if ($this->input->post('proj_stop') == '') {
                $Return['error'] = 'Project Stop Date Required';
            } else if ($this->input->post('proj_des') == '') {
                $Return['error'] = 'Project Description Required';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }

            $data = array(
                'crm_com_proj_for' => $this->input->post('proj_for'),
                'crm_com_proj_title' => $this->input->post('proj_title'),
                'crm_com_proj_start' => $this->input->post('proj_s_date'),
                'crm_com_proj_stop' => $this->input->post('proj_stop'),
                'crm_com_proj_des' => $this->input->post('proj_des'),
                'created_at' => date('Y-m-d h:i:s'),
                'created_by' => $_SESSION['username']['user_id']
            );

            $msg = $this->db->insert('crm_com_proj', $data);

            if ($msg) {
                $Return['result'] = 'Project added Successful';
            } else {
                $Return['error'] = 'Failed to add Project';
            }

            $this->output($Return);
            exit;
        }
    }
    public function add_crm_com_quote()
    {
        $session = $this->session->userdata('username');
        if ($this->input->post('add_type') == 'crm_quote') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('proj') == '') {
                $Return['error'] = 'select a Project';
            } else if ($this->input->post('attn') == '') {
                $Return['error'] = 'Attn Required';
            } else if ($this->input->post('amnt') == '') {
                $Return['error'] = 'Attn Required';
            } else if ($this->input->post('letter') == '') {
                $Return['error'] = 'Letter of Acceptance No Required';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }

            $data = array(
                'com_qt_for' => $this->input->post('quote_for'),
                'com_proj_id' => $this->input->post('proj'),
                'com_quote_attn' => $this->input->post('attn'),
                'com_quote_amnt' => $this->input->post('amnt'),
                'com_quote_letter' => $this->input->post('letter'),
                'created_at' => date('Y-m-d h:i:s'),
                'created_by' => $_SESSION['username']['user_id']
            );

            $msg = $this->db->insert('crm_com_quote', $data);

            if ($msg) {
                $Return['result'] = 'Quotation added Successful';
            } else {
                $Return['error'] = 'Failed to add Quotation';
            }

            $this->output($Return);
            exit;
        }
    }
    public function crm_com_quotation_list($id)
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        // $data['get_quote']=$this->crm_model->get_crm_quoet($id);

        if (!empty($session)) {
            $this->load->view("admin/crm/dialog_com_profile_read", $data, TRUE);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Xin_model->user_role_resource();
        $get_quote = $this->crm_model->get_crm_com_quoet($id);
     

        $data = array();

        $i = 0;
        foreach ($get_quote as $r) {
            $i++;
            // $profile='<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><a href="' . site_url() . 'admin/crm/view_profile/' . $r->crm_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
            $edit =     '<a class="btn btn-outline-success btn-circle btn-md"  data-toggle="modal" data-target=".edit-modal-data"  data-crm_quote_id="' . $r->crm_q_id  . '"><i class="sl-icon-note"></i></a>';
            $delete =   '<a class="btn btn-outline-danger btn-circle btn-md com-quote-delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->crm_q_id  . '"><span class="fa fa-trash"></span></a>';
            // $profile =  '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-crm_id="'. $r->crm_id  . '"><span class="fa fa-eye"></span></button></span>';

            $combhr = $edit . $delete;
            // $combhr =$profile. $edit . $delete;

            $data[] = array(
                $i,
                $r->q_title,
                $r->proj_name,             
                $r->quote_pic,             
                number_format($r->total_item_amount, 2),
                $r->q_date,
                $r->status,
                $combhr,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($get_quote),
            "recordsFiltered" => count($get_quote),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    public function crm_com_invoice_list($id)
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        // $get_quote=$this->crm_model->get_crm_com_quoet($id);


        if (!empty($session)) {
            $this->load->view("admin/crm/dialog_com_profile_read", $data, TRUE);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Xin_model->user_role_resource();
        $get_quote = $this->db->where('quote_for',$id)->get('crm_quotation')->result();

        $data = array();

        $i = 0;
        foreach ($get_quote as $r) {
            $i++;
            $invoice = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/crm/pdf_create/' . $r->crm_q_id . '" target="_blank"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';


            $combhr = $invoice;
            // $combhr =$profile. $edit . $delete;

            $data[] = array(
                $i,
                $combhr,
                $r->q_title,
                $r->proj_name,
                date('d-M-y', strtotime($r->created_at))
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($get_quote),
            "recordsFiltered" => count($get_quote),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    public function com_invoice_pdf($id)
    {
        $get_com = $this->crm_model->get_crm_com_quoet_pdf($id);
        // print_r($get_com);exit;
        $data = [
            'invoice_no' => $get_com[0]->crm_com_qt_id,
            'project_name' => $get_com[0]->crm_com_proj_title,
            'client_company_name' => $get_com[0]->company_name,
            'attn_name' => $get_com[0]->com_quote_attn,
            'email' => $get_com[0]->c_email,
            'project_des' => $get_com[0]->crm_com_proj_des,
            'quote_amnt' => $get_com[0]->com_quote_amnt,
            'invoice_date' => date('d-M-y', strtotime($get_com[0]->created_at)),
            'settings' => $this->Xin_model->read_company_setting_info(1),
            'invoice_settings' => $this->Xin_model->read_setting_info(1)
        ];
        // print_r($data);exit;
        $this->load->view('admin/crm/dialog_com_invoice_pdf', $data);
    }






    //////////////////////////////////////////COMPANY FUNCTIONS END///////////////////////////////////////////


    public function tabUpdate()
    {
        $id = $this->input->post('crm_id');
        $data = array(
            'customer_name' => $this->input->post('cust_name'),
            'contact_number' => $this->input->post('cust_number'),
            'email' => $this->input->post('custmr_email'),
            'postal_code' => $this->input->post('po_code'),
            'cust_address' => $this->input->post('indv_addres'),
            'unit_number' => $this->input->post('u_num'),
            'credit_limit' => $this->input->post('c_limit'),
            'created_by' => $_SESSION['username']['user_id'],
            'created_datetime' => date('Y-m-d h:i:s')
        );
        $res = $this->db->update('crm_customer', $data, ['crm_id' => $id]);
        if ($res) {
            $Return['result'] = "Update Successfull";
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $Return['error'] = "Update Failed";
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    public function add_crm_proj()
    {
        $session = $this->session->userdata('username');
        if ($this->input->post('add_type') == 'crm_proj') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /*ERRORS WILL RENDEDR DEPEND ON SELECT BOX VALUE*/
            if ($this->input->post('proj_title') == '') {
                $Return['error'] = 'Project Title Required';
            } else if ($this->input->post('proj_s_date') == '') {
                $Return['error'] = 'Project Start Date Required';
            } else if ($this->input->post('proj_stop') == '') {
                $Return['error'] = 'Project Stop Date Required';
            } else if ($this->input->post('proj_des') == '') {
                $Return['error'] = 'Project Description Required';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }

            $data = array(
                'proj_for' => $this->input->post('proj_for'),
                'crm_proj_title' => $this->input->post('proj_title'),
                'crm_proj_start' => $this->input->post('proj_s_date'),
                'crm_proj_stop' => $this->input->post('proj_stop'),
                'crm_proj_des' => $this->input->post('proj_des'),
                'created_at' => date('Y-m-d h:i:s'),
                'created_by' => $_SESSION['username']['user_id']
            );

            $msg = $this->db->insert('crm_project', $data);

            if ($msg) {
                $Return['result'] = 'Project added Successful';
            } else {
                $Return['error'] = 'Failed to add Project';
            }

            $this->output($Return);
            exit;
        }
        
    }
    public function view_profile($id)
    {
        $session = $this->session->userdata('username');

        $role_resources_ids  = $this->Xin_model->user_role_resource();

        // print_r($role_resources_ids);exit;           
        $getindv_cust = $this->crm_model->get_inv_profile($id);
       
        $data = array(
            'title' => $this->Xin_model->site_title(),
            'breadcrumbs' => "Individual Customer Profile",
            'path_url' => 'crm',
            'crm_id' => $getindv_cust[0]->crm_id,
            'customer_name' => $getindv_cust[0]->customer_name,
            'contact_number' => $getindv_cust[0]->contact_number,
            'email' => $getindv_cust[0]->email,
            'postal_code' => $getindv_cust[0]->postal_code,
            'cust_address' => $getindv_cust[0]->cust_address,
            'unit_number' => $getindv_cust[0]->unit_number,
            'credit_limit' => $getindv_cust[0]->credit_limit,
            'get_all_proj' => $this->crm_model->get_all_crm_indv_proj($id),
            'get_term_condition' => $this->Xin_model->get_term_condition()->result(),
            'all_units' => $this->Xin_model->get_unit()->result(),
            'all_projects'=>$this->Xin_model->get_all_project(),
            'all_suppliers'=> $this->Xin_model->all_suppliers(),
            'all_payment_terms'=>$this->Xin_model->get_payment_term()->result(),
            'all_shipping_terms'=>$this->db->get('xin_shipping_term')->result(),
            'get_gst'=> $this->Xin_model->get_gst()->result(),
            
        );
       

        if (in_array('3301', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view('admin/crm/dialog_indv_profile_read',$data,true);
                $this->load->view('admin/layout/pms/layout_pms', $data); //page load

            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }
    public function get_term_details()
    {

        $id = $this->uri->segment(4);
        $get_data = $this->crm_model->get_term_details($id);

        echo json_encode($get_data);
    }






    public function project_list_crm_indv($id)
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        $data['get_proj'] = $this->crm_model->get_crm_proj($id);

        if (!empty($session)) {
            $this->load->view("admin/crm/dialog_indv_profile_read", $data, TRUE);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Xin_model->user_role_resource();
        $get_proj = $this->crm_model->get_crm_proj($id);

        $data = array();

        $i = 0;
        foreach ($get_proj as $r) {
            $i++;
            // $profile='<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><a href="' . site_url() . 'admin/crm/view_profile/' . $r->crm_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
            $edit =     '<a class="btn btn-outline-success btn-circle btn-md"  data-toggle="modal" data-target=".edit-modal-data"  data-proj_id="' . $r->crm_proj_id . '"><i class="sl-icon-note"></i></a>';
            $delete =   '<a class="btn btn-outline-danger btn-circle btn-md proj-delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->crm_proj_id . '"><i class="sl-icon-trash"></i></a>';
            // $profile =  '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-crm_id="'. $r->crm_id  . '"><span class="fa fa-eye"></span></button></span>';

            $combhr = $edit . $delete;
            // $combhr =$profile. $edit . $delete;

            $data[] = array(
                $i,
                $r->crm_proj_title,
                $r->crm_proj_start,
                $r->crm_proj_stop,
                $r->crm_proj_des,
                $combhr,
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($get_proj),
            "recordsFiltered" => count($get_proj),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    // public function add_crm_quote()
    // {
    //     $session = $this->session->userdata('username');
    //     if ($this->input->post('add_type') == 'crm_quote') {
    //         /* Define return | here result is used to return user data and error for error message */
    //         $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
    //         $Return['csrf_hash'] = $this->security->get_csrf_hash();

    //         if ($this->input->post('proj') == '') {
    //             $Return['error'] = 'select a Project';
    //         } else if ($this->input->post('attn') == '') {
    //             $Return['error'] = 'Attn Required';
    //         } else if ($this->input->post('amnt') == '') {
    //             $Return['error'] = 'Attn Required';
    //         } else if ($this->input->post('letter') == '') {
    //             $Return['error'] = 'Letter of Acceptance No Required';
    //         }

    //         if ($Return['error'] != '') {
    //             $this->output($Return);
    //             exit;
    //         }

    //         $data = array(
    //             'quote_for' => $this->input->post('quote_for'),
    //             'indv_proj_id' => $this->input->post('proj'),
    //             'indv_quote_attn' => $this->input->post('attn'),
    //             'quote_amnt' => $this->input->post('amnt'),
    //             'quote_letter' => $this->input->post('letter'),
    //             'term_condition_id' => $this->input->post('term_condition_id'),
    //             'term_condition' => $this->input->post('terms_condition'),
    //             'created_at' => date('Y-m-d h:i:s'),
    //             'created_by' => $_SESSION['username']['user_id']
    //         );

    //         $msg = $this->db->insert('crm_quotation', $data);

    //         if ($msg) {
    //             $Return['result'] = 'Quotation added Successful';
    //         } else {
    //             $Return['error'] = 'Failed to add Quotation';
    //         }



    //         $this->output($Return);
    //         exit;

    //     }
    // }
    public function add_crm_quote()
    {
        $session = $this->session->userdata('username');
        if ($this->input->post('add_type') == 'crm_quote') {
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if($this->input->post('project_id')==='') {
				$Return['error'] = $this->lang->line('xin_error_project_field');
			}else if($this->input->post('q_title')===''){
				$Return['error'] = "Quotation Title Required";
			} 
			else if($this->input->post('pay_term')===''){
				$Return['error'] = "Payment Term Required";
			} 
			else if($this->input->post('ship_term')===''){
				$Return['error'] = "Shipping Term Required";
			} 
			else if($this->input->post('pic_name')===''){
				$Return['error'] = "PIC Name Required";
			} 
			else if($this->input->post('pic_email')===''){
				$Return['error'] = "PIC Email Required";
			}
			else if($this->input->post('pic_phone')===''){
				$Return['error'] = "PIC Phone Required";
			} 
			else if($this->input->post('customer_id')==='') {
				$Return['error'] = $this->lang->line('error_customer_required_field');
			}else if (!filter_var($this->input->post('pic_email'), FILTER_VALIDATE_EMAIL)) {
				$Return['error'] = $this->lang->line('xin_employee_error_invalid_email');
			}else if($this->input->post('is_gst') != "1"){
				// if($this->input->post('gst') == ""){
				// 	$Return['error'] = "GST is Required Field";
				// }
			}
			else if($this->input->post('quotation_for')==='') {
				$Return['error'] = $this->lang->line('error_quotation_for_required_field');
			}

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }
            $data = array(
				'quote_for' 					=> $this->input->post('quote_for'),
				'q_title'						=> $this->input->post('q_title'),
				'q_date'						=> $this->input->post('q_validity'),
				'proj_name'						=> $this->input->post('proj_name'),
				'term_condition_id' 			=> $this->input->post('term_condition_id'),
				'term_condition' 				=> $this->input->post('terms_condition'),
				'project_s_add'					=> $this->input->post('project_s_add'),
				'quote_pic' 					=> $this->input->post('pic_name'),
				'quote_email' 					=> $this->input->post('pic_email'),
				'quote_phone' 					=> $this->input->post('pic_phone'),
				'ship_term'						=> $this->input->post('ship_term'),
				'pay_term'						=> $this->input->post('pay_term'),
				'total_item_amount' 			=> $this->input->post('sub_total'),
				'is_gst_inclusive'				=> $this->input->post('is_gst'),
				'gst' 							=> $this->input->post('gst'),
				'gst_value'						=> $this->input->post('sub_total')*($this->input->post('gst')/100),
				'total' 						=> $this->input->post('total_amount'),
				'status'						=> "Draft",
				'remark'						=> $this->input->post('remark'),
				'created_by' 					=> $_SESSION['username']['user_id'],
				'created_at'  			=> date('d-m-Y'),
			);
            $this->db->insert('crm_quotation', $data);
            $msg = $this->db->insert_id();
            if ($msg) {
                $u_data = array(
                    'quotation_no'=> 'PTS/QTN/'.date('Y').'/'.date('m').'/'.$msg
                );
                $this->crm_model->update($u_data, $msg);
            }
            if (isset($_POST['task_name']) && count($this->input->post('task_name')) > 0) {
                for ($i = 0; $i < count($this->input->post('task_name')); $i++) {
                    $task_data = array(
                        'crm_q_id'      => $msg,
                        'task'              => $this->input->post('task_name')[$i],
                        'task_description'      => $this->input->post('task_description')[$i],
                        'created_by'        => $_SESSION['username']['user_id'],
                        // 'created_datetime'  => date('d-m-Y'),
                    );
                    $this->db->insert('crm_quotation_task', $task_data);
                    $task_id = $this->db->insert_id();
                }

                    if (count($this->input->post('description')) > 0) {
                        for ($j = 0; $j < count($this->input->post('description')); $j++) {
                            $subtask_data = array(
                                'crm_quotation_id'      => $msg,
                                'crm_task_id'           => $task_id,
                                'description'       => $this->input->post('description')[$j],
                                'detail'        => $this->input->post('detail')[$j],
                                'unit_id'       => $this->input->post('unit_id')[$j],
                                'unit_rate'         => $this->input->post('unit_rate')[$j],
                                'created_by'        => $_SESSION['username']['user_id'],
                                'qtn' => $this->input->post('gross_price')[$j]
                                // 'created_datetime'  => date('d-m-Y'),
                            );
                         $this->db->insert('crm_quotation_subtask', $subtask_data);
                        }
                    }
            }


            $Return['result'] = 'Quotation added Successful';
            if ($msg) {
                $Return['result'] = 'Quotation added Successful';
            } else {
                $Return['error'] = 'Failed to add Quotation';
            }

            $this->output($Return);
            exit;
        }
    
    }

    public function crm_indv_quotation_list($id)
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        // $data['get_quote']=$this->crm_model->get_crm_quoet($id);

        if (!empty($session)) {
            $this->load->view("admin/crm/dialog_indv_profile_read", $data, TRUE);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Xin_model->user_role_resource();
        $get_quote = $this->crm_model->get_crm_quoet($id);
       

        $data = array();

        $i = 0;
        foreach ($get_quote as $r) {
            $i++;
            // $profile='<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><a href="' . site_url() . 'admin/crm/view_profile/' . $r->crm_id . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-eye"></span></button></a></span>';
            $edit =     '<a class="btn btn-outline-success btn-circle btn-md"  data-toggle="modal" data-target=".edit-modal-data"  data-quote_id="' . $r->crm_q_id . '"><i class="sl-icon-note"></i></a>';
            $delete =   '<a class="btn btn-outline-danger btn-circle btn-md quote-delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->crm_q_id . '"><i class="sl-icon-trash"></i></a>';
            // $profile =  '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-crm_id="'. $r->crm_id  . '"><span class="fa fa-eye"></span></button></span>';

            $combhr = $edit . $delete;
            // $combhr =$profile. $edit . $delete;

            $data[] = array(
                $i,
                $r->quotation_no,
                $r->q_title,
                $r->proj_name,             
                $r->quote_pic,             
                number_format($r->total_item_amount, 2),
                $r->q_date,
                $r->status,
                $combhr,
                
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($get_quote),
            "recordsFiltered" => count($get_quote),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    public function crm_indv_invoice_list($id)
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        // $data['get_quote']=$this->crm_model->get_crm_quoet($id);

        if (!empty($session)) {
            $this->load->view("admin/crm/dialog_indv_profile_read", $data, TRUE);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Xin_model->user_role_resource();
        $get_quote = $this->db->where('quote_for',$id)->get('crm_quotation')->result();

        $data = array();

        $i = 0;
        foreach ($get_quote as $r) {
            $i++;
            $invoice = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_download') . '"><a href="' . site_url() . 'admin/crm/pdf_create/' . $r->crm_q_id . '" target="_blank"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"><span class="fa fa-download"></span></button></a></span>';


            $combhr = $invoice;
            // $combhr =$profile. $edit . $delete;

            $data[] = array(
                $i,
                $combhr,
                $r->q_title,
                $r->proj_name,
                date('d-M-y', strtotime($r->created_at))
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($get_quote),
            "recordsFiltered" => count($get_quote),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }

    public function pdf_create(){
		
		$role_resources_ids = $this->Xin_model->user_role_resource();
		$id = $this->uri->segment(4);
	
		$get_quotations = $this->db->select('crm_quotation.*,crm_customer.crm_id,crm_customer.customer_name,crm_customer.name')
                                   ->join('crm_customer','crm_quotation.quote_for=crm_customer.crm_id')
                                   ->where('crm_q_id',$id)
                                   ->get('crm_quotation')
                                   ->result();
		
			$data = array(
					'quotation_id ' => $get_quotations[0]->crm_q_id,
					'quotation_no' => $get_quotations[0]->quotation_no,
					'quotation_amount' => $get_quotations[0]->total,
					
					'customer_id' => $get_quotations[0]->quote_for,
					'customer_name'=>$get_quotations[0]->customer_name,
					'name'=>$get_quotations[0]->name,
					'project_name' => $get_quotations[0]->q_title,
					'q_date'=>$get_quotations[0]->q_date,
					'term_condition' => $get_quotations[0]->term_condition,
					'status' => $get_quotations[0]->status,
					'created_datetime'=>$get_quotations[0]->created_at,					
					'address' =>$get_quotations[0]->project_s_add,
					'get_all_task'=>$this->Quotation_model->get_tasks($id),
					'get_all_subtasks'=>$this->Quotation_model->get_subtasks($id),
					'all_units'=>$this->Xin_model->get_unit(),
					'quote_pic'=>$get_quotations[0]->quote_pic,
					'quote_email'=>$get_quotations[0]->quote_email,
					'quote_phone'=>$get_quotations[0]->quote_phone,
					'total_item_amount'=>$get_quotations[0]->total_item_amount,
					'gst'=>$get_quotations[0]->gst,
					'gst_value'=>$get_quotations[0]->gst_value,
					);
					
			$this->load->view('admin/finance/get_quotation_receipt',$data);
            // $this->load->view('admin/crm/dialog_indv_invoice_pdf', $data);
		
	}

    // public function invoice_pdf($id)
    // {
    //     $get_inv = $this->crm_model->get_crm_quoet_pdf($id);
    //     // print_r($get_inv);exit;
    //     $data = [
    //         'invoice_no' => $get_inv[0]->crm_q_id,
    //         'project_name' => $get_inv[0]->crm_proj_title,
    //         'client_company_name' => $get_inv[0]->customer_name,
    //         'attn_name' => $get_inv[0]->indv_quote_attn,
    //         'email' => $get_inv[0]->email,
    //         'project_des' => $get_inv[0]->crm_proj_des,
    //         'quote_amnt' => $get_inv[0]->quote_amnt,
    //         'invoice_date' => date('d-M-y', strtotime($get_inv[0]->created_at)),
    //         'settings' => $this->Xin_model->read_company_setting_info(1),
    //         'invoice_settings' => $this->Xin_model->read_setting_info(1)
    //     ];

    //     $this->load->view('admin/crm/dialog_indv_invoice_pdf', $data);
    // }
    public function crmlist()
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        $data['get_indv_cust']= $this->db->where('cust_type',"Individual")->get('crm_customer');
        // $data['get_company_cust']= $this->crm_model->company_cust();
        // print_r($data['get_company_cust']);exit();

        if (!empty($session)) {
            $this->load->view("admin/crm/crm_list", $data, TRUE);
        } else {
            redirect('admin/');
        }

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $role_resources_ids = $this->Xin_model->user_role_resource();
        $get_indv_cust = $this->db->where('cust_type',"Individual")->get('crm_customer');

        $data = array();
        // print_r($get_indv_cust->result());
        $i = 0;
        foreach ($get_indv_cust->result() as $r) {
            $i++;
            $profile = '<a href="' . site_url() . 'admin/crm/view_profile/' . $r->crm_id . '">'. $r->customer_name.'</a>';
            $edit =     '<a class="btn btn-outline-success btn-circle btn-md"  data-toggle="modal" data-target=".edit-modal-data"  data-crm_id="' . $r->crm_id . '"><i class="sl-icon-note"></i></a>';
            $delete =   '<a class="btn btn-outline-danger btn-circle btn-md  delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->crm_id . '"><i class="sl-icon-trash"></i></a>';
            // $profile =  '<span data-toggle="tooltip" data-placement="top" title="'.$this->lang->line('xin_profile').'"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".view-modal-data"  data-crm_id="'. $r->crm_id  . '"><span class="fa fa-eye"></span></button></span>';

            // $combhr =$edit . $delete;
            $combhr =$edit . $delete;

            $data[] = array(
                $i,
                $profile,
                $r->contact_number,
                $r->email,
                $r->postal_code,
                $r->cust_address,
                $r->unit_number,
                $r->credit_limit,
                $combhr,
            );
        }


        $output = array(
            "draw" => $draw,
            "recordsTotal" => $get_indv_cust->num_rows(),
            "recordsFiltered" => $get_indv_cust->num_rows(),
            "data" => $data
        );



        echo json_encode($output);
        exit();
    }

    public function com_crmlist(){
        $data['title'] = $this->Xin_model->site_title();    
        $session = $this->session->userdata('username');
   
        $data['get_company_cust']= $this->db->where('cust_type',"Company")->get('crm_customer')->result();
        // print_r($data['get_company_cust']);exit();
       
        if (!empty($session)) {
            $this->load->view("admin/crm/crm_list", $data, TRUE);
        } else {
            redirect('admin/');
        }
       
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
       
        $role_resources_ids = $this->Xin_model->user_role_resource();
 
        $get_company_cust=$this->db->where('cust_type',"Company")->get('crm_customer');
        $data = array();
        // print_r($get_company_cust->result());
       
       
        $i=0;foreach ($get_company_cust->result() as $r) {$i++;
            $profile = '<a href="' . site_url() . 'admin/crm/view_com_profile/' . $r->crm_id . '">'. $r->name.'</a>';
            $edit = '<a class="btn btn-outline-success btn-circle btn-md"  data-toggle="modal" data-target=".edit-modal-data"  data-crm_c_id="'. $r->crm_id  . '"><i class="sl-icon-note"></i></a>';
            $delete = '<a class="btn btn-outline-danger btn-circle btn-md  delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->crm_id . '"><i class="sl-icon-trash"></i></a>';      
       
            $combhr = $edit . $delete;
       
            $data[] = array(
                $i,
                $profile,
                $r->company_name,
                $r->company_uen,
                $r->c_contact_number,
                $r->c_email,
                $r->c_postal_code,
                $r->address,
                $r->c_unit_number,
                $r->c_credit_limit,
                $combhr
            );
        }
       
        $output = array(
            "draw" => $draw,
            "recordsTotal" =>  $get_company_cust->num_rows(),
            "recordsFiltered" =>  $get_company_cust->num_rows(),
            "data" => $data
        );
 
 
       
        echo json_encode($output);
        exit();
       
    }

    public function add_crm()
    {
        $session = $this->session->userdata('username');

        if ($this->input->post('add_type') == 'crm') {

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /*ERRORS WILL RENDEDR DEPEND ON SELECT BOX VALUE*/
            if($this->input->post('customer')=='company'){
 
                if($this->input->post('com_name')==''){
                    $Return['error']='Company Name Field Required';
                }else if($this->input->post('name')==''){
                    $Return['error']='Name Field Required';
                }else if($this->input->post('pos_code')==''){
                    $Return['error']='Postal Code Field Required';
                }else if($this->input->post('un_num')==''){
                    $Return['error']='Unit Number Field Required';
                }

                if($Return['error']!=''){
                    $this->output($Return);
                    exit;
                }

             
        $data = array(
            'company_name' => $this->input->post('com_name'),
            'name' => $this->input->post('name'),
            'company_uen'=>$this->input->post('c_uen'),
            'c_contact_number' => $this->input->post('c_number'),
            'c_email' => $this->input->post('cust_email'),
            'c_postal_code' => $this->input->post('pos_code'),
            'address' => $this->input->post('com_address'),
            'c_unit_number' => $this->input->post('un_num'),
            'c_credit_limit' => $this->input->post('cr_limit'),
            'cust_type' => "Company",
             'created_by' => $_SESSION['username']['user_id'],
             'created_datetime' => date('Y-m-d h:i:s'),
            );
           
            $this->db->insert('crm_customer',$data); //after successfull data insert it will return insert id
            $result = $this->db->insert_id();
           

            if ($result) {
                if(count($this->input->post('pr_ic')) > 0){
                for($i=0;$i<count($this->input->post('a_dd'));$i++){
                    $data_opt = array(
                    'crm_company_cust_id' => $result,
                    'p_ic'=>$this->input->post('pr_ic')[$i],
                    'c_n' => $this->input->post('c_n')[$i],
                    'e_mail' => $this->input->post('e_mail')[$i],
                    'a_dd' => $this->input->post('a_dd')[$i],                          
                    'created_by' => $_SESSION['username']['user_id'],
                    'created_datetime' => date('Y-m-d h:i:s'),
                    // 'status'=>$res
                    );
                    $this->crm_model->add_items($data_opt);
                }
                $Return['result'] = "Customer Company added Successfull";
            } else {
                $Return['error'] = "Something Went Wrong";
            }
            $this->output($Return);
            exit;
            }

            /*ERRORS WILL RENDEDR DEPEND ON SELECT BOX VALUE*/
            } else if ($this->input->post('customer') == 'individual') {

                // print_r($_POST);exit;

                if ($this->input->post('cust_name') == '') {
                    $Return['error'] = 'Customer Name Field Required';
                } else if ($this->input->post('po_code') == '') {
                    $Return['error'] = 'Postal Code Field Required';
                } else if ($this->input->post('u_num') == '') {
                    $Return['error'] = 'Unit Number Field Required';
                }

                if ($Return['error'] != '') {
                    $this->output($Return);
                    exit;
                }

                $data = array(
                    'customer_name' => $this->input->post('cust_name'),
                    'contact_number' => $this->input->post('cust_number'),
                    'email' => $this->input->post('custmr_email'),
                    'postal_code' => $this->input->post('po_code'),
                    'cust_address' => $this->input->post('indv_addres'),
                    'unit_number' => $this->input->post('u_num'),
                    'cust_type' => "Company",
                    'credit_limit' => $this->input->post('c_limit'),
                    'created_by' => $_SESSION['username']['user_id'],
                    'created_datetime' => date('Y-m-d h:i:s')
                );
                $this->db->insert('crm_customer',$data); //after successfull data insert it will return insert id
                 $result = $this->db->insert_id(); //after successfull data insert it will return insert id
                if ($result) {
                    if (count($this->input->post('pr_ic')) > 0) {
                        for ($i = 0; $i < count($this->input->post('a_dd')); $i++) {
                            $data_opt = array(
                                'crm_company_cust_id' => $result,
                                'p_ic' => $this->input->post('pr_ic')[$i],
                                'c_n' => $this->input->post('c_n')[$i],
                                'e_mail' => $this->input->post('e_mail')[$i],
                                'a_dd' => $this->input->post('a_dd')[$i],
                                'created_by' => $_SESSION['username']['user_id'],
                                'created_datetime' => date('Y-m-d h:i:s'),
                                // 'status'=>$res
                            );
                            $this->crm_model->add_items($data_opt);
                        }
                        $Return['result'] = "Customer Company added Successfull";
                    } else {
                        $Return['error'] = "Something Went Wrong";
                    }
                    $Return['result'] = "Individual Customer added Successfull";
                } else {
                    $Return['result'] = "Something Went Wrong";
                }
                $this->output($Return);
                exit;
            } else if ($this->input->post('customer') === '') {
                $Return['error'] = "Please Select Customer Type";
                if ($Return['error'] != '') {
                    $this->output($Return);
                    exit;
                }
            }
        } //end add type
    } // add crm end


    public function read()
    {
        $session = $this->session->userdata('username');
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('crm_id');

        $result = $this->db->where('crm_id',$id)->get('crm_customer')->result();
        // $this->crm_model->read_indv_cust($id);
        // print_r($session);exit();
        // $data = '';
        if (!empty($result)) {
            $data = array(
                'crm_id' => $result[0]->crm_id,
                'customer_name' => $result[0]->customer_name,
               
                'contact_number' => $result[0]->contact_number,
                'email' => $result[0]->email,
                'postal_code' => $result[0]->postal_code,
                'cust_address' => $result[0]->cust_address,
                'unit_number' => $result[0]->unit_number,
                'credit_limit' => $result[0]->credit_limit
            );
          
              
            
        }
        //    echo "<pre>"; print_r($data);exit;


        if (!empty($session)) {
            $this->load->view('admin/crm/dialog_crm', $data);
        } else {
            redirect('admin/');
        }
    }

    public function indv_pro_read()
    {
        $session = $this->session->userdata('username');
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('crm_id');

        $result = $this->crm_model->indv_pro_read($id);
        // print_r($session);exit();
        $data = '';
        if (!empty($result)) {
            $data = array(
                'crm_proj_id' => $result[0]->crm_proj_id,
                'crm_proj_title' => $result[0]->crm_proj_title,
                'proj_for' => $result[0]->proj_for,
                'crm_proj_start' => $result[0]->crm_proj_start,
                'crm_proj_stop' => $result[0]->crm_proj_stop,
                'crm_proj_des' => $result[0]->crm_proj_des,

            );
        }
        //    echo "<pre>"; print_r($data);exit;


        if (!empty($session)) {
            $this->load->view('admin/crm/dialog_indv_project', $data);
        } else {
            redirect('admin/');
        }
    }
    public function indv_quote_read()
    {
         $id = $this->input->get('crm_id');
       

        $session = $this->session->userdata('username');
		$data['title'] = 'Quotation | '.$this->Xin_model->site_title();
        $data['breadcrumbs'] = 'Quotation';
		$data['path_url'] = 'quotation';
		$data['result'] = $this->Quotation_model->read_quotation_data($id);

        // $data['get_all_projects'] = $this->Xin_model->get_all_project();
        // $data['all_suppliers'] = $this->Xin_model->all_suppliers();
		// $data['get_all_customer'] = $this->Quotation_model->get_clients();
		// $data['all_countries'] = $this->Xin_model->get_countries();
		$data['all_units'] = $this->Xin_model->get_unit();
       
		$data['get_gst'] = $this->Xin_model->get_gst()->result();
		// $data['get_all_task'] = $this->Quotation_model->get_tasks($id);
		$data['get_all_task'] = $this->Xin_model->get_tasks($id);
		// $data['get_all_subtasks'] = $this->Quotation_model->get_subtasks($id);
		$data['get_all_subtasks'] = $this->Xin_model->get_subtasks($id);
		$data['all_payment_terms'] = $this->Xin_model->get_payment_term()->result();
        $data['all_shipping_terms']=$this->db->get('xin_shipping_term')->result();
		$data['get_term_condition'] = $this->Xin_model->get_term_condition()->result();
        


        
	
		$role_resources_ids = $this->Xin_model->user_role_resource();

        if (!empty($session)) {
            $this->load->view("admin/crm/dialog_indv_quotation", $data);
           
        } else {
            redirect('admin/');
        }
    }


    public function crm_com_project_read()
    {
        $session = $this->session->userdata('username');
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('crm_id');

        $result = $this->crm_model->com_project_read($id);
        // print_r($session);exit();
        $data = '';
        if (!empty($result)) {
            $data = array(
                'crm_com_proj_id' => $result[0]->crm_com_proj_id,
                'crm_com_proj_title' => $result[0]->crm_com_proj_title,
                'crm_com_proj_for' => $result[0]->crm_com_proj_for,
                'crm_com_proj_start' => $result[0]->crm_com_proj_start,
                'crm_com_proj_stop' => $result[0]->crm_com_proj_stop,
                'crm_com_proj_des' => $result[0]->crm_com_proj_des,

            );
        }
        if (!empty($session)) {
            $this->load->view('admin/crm/dialog_com_project', $data);
        } else {
            redirect('admin/');
        }
    }

    public function crm_com_quote_read()
    {
        $session = $this->session->userdata('username');
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('crm_id');

        $result = $this->crm_model->com_quote_read($id);
        // print_r($session);exit();
        $data = '';
        if (!empty($result)) {
            $data = array(
                'crm_com_qt_id' => $result[0]->crm_com_qt_id,
                'com_qt_for' => $result[0]->com_qt_for,
                'com_proj_id' => $result[0]->com_proj_id,
                'com_quote_attn' => $result[0]->com_quote_attn,
                'com_quote_amnt' => $result[0]->com_quote_amnt,
                'com_quote_letter' => $result[0]->com_quote_letter,

            );
        }

        $data['projects'] = $this->crm_model->com_cust_pro_read($result[0]->com_qt_for);
        if (!empty($session)) {
            $this->load->view('admin/crm/dialog_com_quotation', $data);
        } else {
            redirect('admin/');
        }
    }

    public function comcrm_read()
    {
        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('crm_c_id');

        $result = $this->crm_model->read_com_cust($id);
// print_r($result);exit;   
        if(!empty($result)){
            $data=array(
                'crm_id'=>$result[0]->crm_id,
                'c_logo'=>$result[0]->c_logo,
                'company_name' => $result[0]->company_name,
                'name' => $result[0]->name,
                'company_uen'=>$result[0]->company_uen,
                'c_contact_number' =>$result[0]->c_contact_number,
                'c_email' => $result[0]->c_email,
                'c_postal_code' => $result[0]->c_postal_code,
                'address' => $result[0]->address,
                'c_unit_number' => $result[0]->c_unit_number,
                'c_credit_limit' => $result[0]->c_credit_limit,
                // 'crm_company_cust_id' => $result[0]->crm_company_cust_id,

                'p_ic' => $result[0]->p_ic,
                'c_n' => $result[0]->c_n,
                'e_mail' => $result[0]->e_mail,
                'a_dd' => $result[0]->a_dd,
            );

            
        }

        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/crm/dialog_companycrm', $data);
        } else {
            redirect('admin/');
        }

        // if (!empty($result)) {
        //     $data = array(
        //         'crm_id' => $result[0]->crm_id,
        //         'company_name' => $result[0]->company_name,
        //         'company_uen' => $result[0]->company_uen,
        //         'c_contact_number' => $result[0]->c_contact_number,
        //         'c_email' => $result[0]->c_email,
        //         'c_postal_code' => $result[0]->c_postal_code,
        //         'address' => $result[0]->address,
        //         'c_unit_number' => $result[0]->c_unit_number,
        //         'c_credit_limit' => $result[0]->c_credit_limit,
        //         'crm_company_cust_id' => $result[0]->crm_company_cust_id,
        //         'p_ic' => $result[0]->p_ic,
        //         'c_n' => $result[0]->c_n,
        //         'e_mail' => $result[0]->e_mail,
        //         'a_dd' => $result[0]->a_dd,
        //     );
        // }
        // $result = $this->crm_model->read_com_cust($id);
        // // print_r($result);exit();

        // if (!empty($result)) {
        //     $data = array(
        //         'crm_c_id' => $result[0]->crm_c_id,
        //         'company_name' => $result[0]->company_name,
        //         'company_uen' => $result[0]->company_uen,
        //         'c_contact_number' => $result[0]->c_contact_number,
        //         'c_email' => $result[0]->c_email,
        //         'c_postal_code' => $result[0]->c_postal_code,
        //         'address' => $result[0]->address,
        //         'c_unit_number' => $result[0]->c_unit_number,
        //         'c_credit_limit' => $result[0]->c_credit_limit,
        //         'crm_company_cust_id' => $result[0]->crm_company_cust_id,
        //         'p_ic' => $result[0]->p_ic,
        //         'c_n' => $result[0]->c_n,
        //         'e_mail' => $result[0]->e_mail,
        //         'a_dd' => $result[0]->a_dd,
        //     );
        // } else {
        //     $result = $this->db->where('crm_c_id', $id)->get('crm_company_cust')->result();
        //     $data = array(
        //         'crm_c_id' => $result[0]->crm_c_id,
        //         'company_name' => $result[0]->company_name,
        //         'company_uen' => $result[0]->company_uen,
        //         'c_contact_number' => $result[0]->c_contact_number,
        //         'c_email' => $result[0]->c_email,
        //         'c_postal_code' => $result[0]->c_postal_code,
        //         'address' => $result[0]->address,
        //         'c_unit_number' => $result[0]->c_unit_number,
        //         'c_credit_limit' => $result[0]->c_credit_limit,
        //     );
        // }



        // if (empty($result)) {
        //     $result = $this->crm_model->read_only_com_cust($id);
        //     $data = array(
        //         'crm_c_id' => $result[0]->crm_c_id,
        //         'company_name' => $result[0]->company_name,
        //         'company_uen' => $result[0]->company_uen,
        //         'c_contact_number' => $result[0]->c_contact_number,
        //         'c_email' => $result[0]->c_email,
        //         'c_postal_code' => $result[0]->c_postal_code,
        //         'address' => $result[0]->address,
        //         'c_unit_number' => $result[0]->c_unit_number,
        //         'c_credit_limit' => $result[0]->c_credit_limit,
        //         // 'crm_company_cust_id'=>$result[0]->crm_company_cust_id,
        //         // 'p_ic'=>$result[0]->p_ic,
        //         // 'c_n'=>$result[0]->c_n,
        //         // 'e_mail'=>$result[0]->e_mail,
        //         // 'a_dd'=>$result[0]->a_dd,
        //     );
        // }


        // print_r($data);exit;

        
    }


    public function update()
    {

        if ($this->input->post('edit_type') == 'edit_crm') {

            $id = $this->input->post('crm_id');



            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            // if($this->input->post('u_project_id')==='') {
            // 	$Return['error'] = $this->lang->line('error_project_field');
            // } else 
            if ($this->input->post('cust_name') == '') {
                $Return['error'] = 'Customer Name Field Required';
            } else if ($this->input->post('po_code') == '') {
                $Return['error'] = 'Postal Code Field Required';
            } else if ($this->input->post('u_num') == '') {
                $Return['error'] = 'Unit Number Field Required';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }

            $data = array(
                'customer_name' => $this->input->post('cust_name'),
                'contact_number' => $this->input->post('cust_number'),
                'email' => $this->input->post('custmr_email'),
                'postal_code' => $this->input->post('po_code'),
                'cust_address' => $this->input->post('indv_addres'),
                'unit_number' => $this->input->post('u_num'),
                'credit_limit' => $this->input->post('c_limit')
            );

            $result = $this->crm_model->update_indv_data($data, $id);
            if ($result == true) {
                $result = $this->db->delete('crm_company_cust_item_map', ['crm_company_cust_id' => $id]);
                if (count($this->input->post('a_dd')) > 0) {
                    for ($i = 0; $i < count($this->input->post('a_dd')); $i++) {
                        $data_opt = array(
                            'crm_company_cust_id' => $this->input->post('crm_id'),
                            'p_ic' => $this->input->post('pr_ic')[$i],
                            'c_n' => $this->input->post('c_n')[$i],
                            'e_mail' => $this->input->post('e_mail')[$i],
                            'a_dd' => $this->input->post('a_dd')[$i],
                            'created_by' => $_SESSION['username']['user_id'],
                            'created_datetime' => date('Y-m-d h:i:s'),
                            // 'status'=>$res
                        );
                        $this->crm_model->add_items($data_opt);
                    }
                }
                
            }
            if ($result) {
                $Return['result'] = "Individual Customer Update Successfull";
            } else {
                $Return['result'] = "Something Went Wrong";
            }
            $this->output($Return);
            exit;
        }
    }

    public function indv_project_update()
    {
        if ($this->input->post('edit_type') == 'edit_indv_pro_crm') {

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('crm_proj_title') == '') {
                $Return['error'] = 'Customer Name Field Required';
            } else if ($this->input->post('crm_proj_start') == '') {
                $Return['error'] = 'Project Start Date Field Required';
            } else if ($this->input->post('crm_proj_stop') == '') {
                $Return['error'] = 'Project Deadline Field Required';
            } else if ($this->input->post('crm_proj_des') == '') {
                $Return['error'] = 'Project Description Field Required';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }
            $id = $this->input->post('crm_proj_id');
            $data = array(
                'crm_proj_title' => $this->input->post('crm_proj_title'),
                'proj_for' => $this->input->post('proj_for'),
                'crm_proj_start' => $this->input->post('crm_proj_start'),
                'crm_proj_stop' => $this->input->post('crm_proj_stop'),
                'crm_proj_des' => $this->input->post('crm_proj_des')
            );
            $result = $this->crm_model->update_indv_project($data, $id);
            if ($result) {
                $Return['result'] = "Individual Project Update Successfull";
            } else {
                $Return['result'] = "Something Went Wrong";
            }
            $this->output($Return);
            exit;
        }
    }

    public function com_project_update()
    {
        if ($this->input->post('edit_type') == 'edit_com_pro_crm') {

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('crm_com_proj_title') == '') {
                $Return['error'] = 'Customer Name Field Required';
            } else if ($this->input->post('crm_com_proj_start') == '') {
                $Return['error'] = 'Project Start Date Field Required';
            } else if ($this->input->post('crm_com_proj_stop') == '') {
                $Return['error'] = 'Project Deadline Field Required';
            } else if ($this->input->post('crm_com_proj_des') == '') {
                $Return['error'] = 'Project Description Field Required';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }
            $id = $this->input->post('crm_com_proj_id');
            $data = array(
                'crm_com_proj_title' => $this->input->post('crm_com_proj_title'),
                'crm_com_proj_for' => $this->input->post('crm_com_proj_for'),
                'crm_com_proj_start' => $this->input->post('crm_com_proj_start'),
                'crm_com_proj_stop' => $this->input->post('crm_com_proj_stop'),
                'crm_com_proj_des' => $this->input->post('crm_com_proj_des')
            );
            $result = $this->crm_model->update_com_project($data, $id);
            if ($result) {
                $Return['result'] = "Company Project Update Successfull";
            } else {
                $Return['result'] = "Something Went Wrong";
            }
            $this->output($Return);
            exit;
        }
    }

    public function indv_quote_update()
    {
        if ($this->input->post('edit_type') == 'edit_indv_quote_crm') {
            $id = $this->input->post('quotation_id');
    
			$data = array(
				'quote_for' 					=> $this->input->post('crm_i'),
				'q_title'						=> $this->input->post('q_title'),
				'q_date'						=> $this->input->post('q_validity'),
				'proj_name'						=> $this->input->post('proj_name'),
				'term_condition_id' 			=> $this->input->post('term_condition_id'),
				'term_condition' 				=> $this->input->post('terms_condition'),
				'project_s_add'					=> $this->input->post('project_s_add'),
				'quote_pic' 					=> $this->input->post('pic_name'),
				'quote_email' 					=> $this->input->post('pic_email'),
				'quote_phone' 					=> $this->input->post('pic_phone'),
				'ship_term'						=> $this->input->post('ship_term'),
				'pay_term'						=> $this->input->post('pay_term'),
				'total_item_amount' 			=> $this->input->post('sub_total'),
				'is_gst_inclusive'				=> $this->input->post('is_gst'),
				'gst' 							=> $this->input->post('gst'),
				'gst_value'						=> $this->input->post('sub_total')*($this->input->post('gst')/100),
				'total' 						=> $this->input->post('total_amount'),
				'status'						=> "Draft",
				'remark'						=> $this->input->post('remark'),
				'created_by' 					=> $_SESSION['username']['user_id'],
				'created_at'  			=> date('d-m-Y'),
			);
			
			// print_r($_POST);exit;
				$result = $this->db->update('crm_quotation',$data,['crm_q_id'=>$id]);
                
				
				if ($result) { 
					if(isset($_POST['task_name']) && count($this->input->post('task_name')) > 0){
						//  $this->Quotation_model->delete_task($id);
                        // 	$this->Quotation_model->delete_subtask($id);
                            $this->db->delete('crm_quotation_task',['crm_q_id'=>$id]);
                            $this->db->delete('crm_quotation_subtask',['crm_quotation_id'=>$id]);

						
						for($i=0;$i<count($this->input->post('task_name'));$i++){
							$task_data = array(
								'crm_q_id' 			=> $id,
								'task' 				=> $this->input->post('task_name')[$i],
								'task_description' 	=> $this->input->post('task_description')[$i],
								'created_at'  		=> date('d-m-Y'),
								'created_by' 		=> $_SESSION['username']['user_id'],
							);
							$this->db->insert('crm_quotation_task',$task_data);
							$task_id=$this->db->insert_id();
                            
			
							// echo "<pre>";print_r($task_data);"</pre>";
						   
							if(count($this->input->post('description')) > 0){
									for($j=0;$j<count($this->input->post('description'));$j++){
											$subtask_data = array(
												'crm_quotation_id' 	=> $id,
												'crm_task_id' 			=> $task_id,
												'product'			=> $this->input->post('product')[$j],
												'qtn'		        => $this->input->post('qtn')[$j],
												'description' 		=> $this->input->post('description')[$j],
												'detail' 			=> $this->input->post('detail')[$j],
												'unit_id' 			=> $this->input->post('unit_id')[$j],
												'unit_rate' 		=> $this->input->post('unit_rate')[$j],
												'created_by' 		=> $_SESSION['username']['user_id'],
												'created_at'  => date('d-m-Y'),
											);
											$iddd=$this->db->insert('crm_quotation_subtask',$subtask_data);
											// echo "<pre>";print_r($subtask_data);"</pre>";
                                
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
    public function com_quote_update()
    {
        if ($this->input->post('edit_type') == 'edit_com_quote_crm') {

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('com_quote_attn') == '') {
                $Return['error'] = 'Quotation ATTN Field Required';
            } else if ($this->input->post('com_quote_letter') == '') {
                $Return['error'] = 'Quotation Letter Field Required';
            } else if ($this->input->post('com_quote_amnt') == '') {
                $Return['error'] = 'Quotation Amount Field Required';
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }
            $id = $this->input->post('crm_com_qt_id');
            $data = array(
                'com_proj_id' => $this->input->post('com_proj_id'),
                'com_quote_attn' => $this->input->post('com_quote_attn'),
                'com_quote_amnt' => $this->input->post('com_quote_amnt'),
                'com_quote_letter' => $this->input->post('com_quote_letter')

            );
            $result = $this->crm_model->update_com_quotation($data, $id);
            if ($result) {
                $Return['result'] = "Company Quotation Update Successfull";
            } else {
                $Return['result'] = "Something Went Wrong";
            }
            $this->output($Return);
            exit;
        }
    }

    public function com_update()
    {
        if ($this->input->post('edit_type') == 'edit_comcrm') {
            // print_r($_FILES);exit;
            $id = $this->input->post('crm_c_id');
            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            /* Server side PHP input validation */
            // if($this->input->post('u_project_id')==='') {
            // 	$Return['error'] = $this->lang->line('error_project_field');
            // } else 
            if ($this->input->post('com_name') == '') {
                $Return['error'] = 'Company Name Field Required';
            } else if ($this->input->post('name') == '') {
                $Return['error'] = 'Name Field Required';
            } else if ($this->input->post('pos_code') == '') {
                $Return['error'] = 'Postal Code Field Required';
            } else if ($this->input->post('un_num') == '') {
                $Return['error'] = 'Unit Number Field Required';
            }



            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }
            $data = array(
                'company_name' => $this->input->post('com_name'),                
                'company_uen' => $this->input->post('c_uen'),
                'c_contact_number' => $this->input->post('c_number'),
                'c_email' => $this->input->post('cust_email'),
                'c_postal_code' => $this->input->post('pos_code'),
                'address' => $this->input->post('com_address'),
                'c_unit_number' => $this->input->post('un_num'),
                'c_credit_limit' => $this->input->post('cr_limit')              
            );


            if(!empty($_FILES['c_logo'])){

                // $newfilename="";
                if(is_uploaded_file($_FILES['c_logo']['tmp_name'])) {
                    //checking image type
                    $allowed =  array('png','jpg','gif','jpeg');
                    $filename = $_FILES['c_logo']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    
                    if(in_array($ext,$allowed)){
                        $tmp_name = $_FILES["c_logo"]["tmp_name"];
                        $attachment_file = "uploads/crm/";
                        // basename() may prevent filesystem traversal attacks;
                        // further validation/sanitation of the filename may be appropriate
                        $name = basename($_FILES["c_logo"]["name"]);
                        $newfilename = 'company_logo_'.round(microtime(true)).'.'.$ext;
                        move_uploaded_file($tmp_name, $attachment_file.$newfilename);
                        $fname = $newfilename;
                    } else {
                        $Return['error'] = "Something Went Wrong";
                    }
                }
               }else{
                $_FILES['c_logo']='';
                $newfilename="";
               }

               $data['c_logo']=$newfilename;

           

// print_r($_FILES);
// exit;
            $result = $this->crm_model->update_com_data($data, $id);
            if ($result == true) {
                $result = $this->db->delete('crm_company_cust_item_map', ['crm_company_cust_id' => $id]);
                if (count($this->input->post('a_dd')) > 0) {
                    for ($i = 0; $i < count($this->input->post('a_dd')); $i++) {
                        $data_opt = array(
                            'crm_company_cust_id' => $this->input->post('crm_c_id'),
                            'p_ic' => $this->input->post('pr_ic')[$i],
                            'c_n' => $this->input->post('c_n')[$i],
                            'e_mail' => $this->input->post('e_mail')[$i],
                            'a_dd' => $this->input->post('a_dd')[$i],
                            'created_by' => $_SESSION['username']['user_id'],
                            'created_datetime' => date('Y-m-d h:i:s'),
                            // 'status'=>$res
                        );
                        $this->crm_model->add_items($data_opt);
                    }
                }
                /* if($this->input->post('item_ids') !='' && count($this->input->post('item_ids'))>0){
                    for($i=0;$i<count($this->input->post('a_dd'));$i++){
                       $item_id = $this->input->post('item_ids')[$i];
                     $data_opt = array(
                         'crm_company_cust_id' => $this->input->post('crm_c_id'),
                         // 'crm_company_cust_item_id'=>$item_id,
                         'p_ic'=>$this->input->post('pr_ic')[$i],
                         'c_n' => $this->input->post('c_n')[$i],
                         'e_mail' => $this->input->post('e_mail')[$i],
                         'a_dd' => $this->input->post('a_dd')[$i],	                        
                         'modified_by' => $_SESSION['username']['user_id'],
                         'modified_datetime' => date('Y-m-d h:i:s'),
                         
                         );
                         $this->crm_model->update_items($data_opt,$item_id);
                     } 
                   } */
                /* if(empty($this->input->post('item_ids'))){
                    for($i=0;$i<count($this->input->post('a_dd'));$i++){
                        $data_opt = array(
                        'crm_company_cust_id' => $this->input->post('crm_c_id'),
                        'p_ic'=>$this->input->post('pr_ic')[$i],
                        'c_n' => $this->input->post('c_n')[$i],
                        'e_mail' => $this->input->post('e_mail')[$i],
                        'a_dd' => $this->input->post('a_dd')[$i],	                        
                        'created_by' => $_SESSION['username']['user_id'],
                        'created_datetime' => date('Y-m-d h:i:s'),
                        // 'status'=>$res
                        );
                        $this->crm_model->add_items($data_opt);
                    }
                   } */
            }
            if ($result) {
                $Return['result'] = "Company Customer Update Successfull";
            } else {
                $Return['result'] = "Something Went Wrong";
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
        $result = $this->db->delete('crm_ind_cust', ['crm_id' => $id]);
        if ($result == true) {
            $resultProject = $this->db->delete('crm_quotation', ['quote_for' => $id]);
            $resultQuotation = $this->db->delete('crm_project', ['proj_for' => $id]);
        }

        if (isset($id)) {
            $Return['result'] = "Delete Successfull";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
        exit;
    }


    /* indivisual Project delete 20/11/2023*/
    public function delete_proj()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result = $this->db->delete('crm_project', ['crm_proj_id' => $id]);
        if (isset($id)) {
            $Return['result'] = "Delete Successfull";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
        exit;
    }

    public function delete_quote()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result = $this->db->delete('crm_quotation', ['crm_q_id' => $id]);
        $this->db->delete('crm_quotation_task',['crm_q_id'=>$id]);
        $this->db->delete('crm_quotation_subtask',['crm_quotation_id'=>$id]);
        if (isset($id)) {
            $Return['result'] = "Delete Successfull";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
        exit;
    }

    public function delete_com_cust()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result = $this->db->delete('crm_company_cust', ['crm_c_id' => $id]);
        if (isset($id)) {
            $Return['result'] = "Delete Successfull";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
        exit;
    }

    public function delete_com_poject()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result = $this->db->delete('crm_com_proj', ['crm_com_proj_id' => $id]);
        if (isset($id)) {
            $Return['result'] = "Delete Successfull";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
        exit;
    }

    public function delete_com_quote()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        /* Define return | here result is used to return user data and error for error message */
        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $id = $this->uri->segment(4);
        $Return['csrf_hash'] = $this->security->get_csrf_hash();
        $result = $this->db->delete('crm_com_quote', ['crm_com_qt_id' => $id]);
        if (isset($id)) {
            $Return['result'] = "Delete Successfull";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
        exit;
    }


    public function get_details($id)
    {
        $get_all_items = $this->crm_model->get_company_items($id);
        if ($get_all_items) {
            if (count($get_all_items) > 0) {
                for ($i = 0; $i < count($get_all_items); $i++) {
                    $data[] = array(
                        'crm_company_cust_item_id' => $get_all_items[$i]->crm_company_cust_item_id,
                        'p_ic' => $get_all_items[$i]->p_ic,
                        'c_n' => $get_all_items[$i]->c_n,
                        'e_mail' => $get_all_items[$i]->e_mail,
                        'a_dd' => $get_all_items[$i]->a_dd,
                    );

                    $session = $this->session->userdata('username');
                    if (!empty($session)) {
                        $this->load->view("admin/crm/dialog_companycrm", $data);
                    } else {
                        redirect('admin/');
                    }
                    // Datatables Variables
                    $draw = intval($this->input->get("draw"));
                    $start = intval($this->input->get("start"));
                    $length = intval($this->input->get("length"));
                }

                echo json_encode($data);
                exit();
            }
        }
    }
    public function get_details1($id)
    {
        $get_all_items = $this->crm_model->get_company_items($id);
        if ($get_all_items) {
            if (count($get_all_items) > 0) {
                for ($i = 0; $i < count($get_all_items); $i++) {
                    $data[] = array(
                        'crm_company_cust_item_id' => $get_all_items[$i]->crm_company_cust_item_id,
                        'p_ic' => $get_all_items[$i]->p_ic,
                        'c_n' => $get_all_items[$i]->c_n,
                        'e_mail' => $get_all_items[$i]->e_mail,
                        'a_dd' => $get_all_items[$i]->a_dd,
                    );

                    $session = $this->session->userdata('username');
                    if (!empty($session)) {
                        $this->load->view("admin/crm/dialog_crm", $data);
                    } else {
                        redirect('admin/');
                    }
                    // Datatables Variables
                    $draw = intval($this->input->get("draw"));
                    $start = intval($this->input->get("start"));
                    $length = intval($this->input->get("length"));
                }

                echo json_encode($data);
                exit();
            }
        }
    }

    public function get_com_cust_details()
    {
        $id = $this->uri->segment(4);

        $result = $this->crm_model->get_company_cust_id($id);

        // $get_all_items = $this->crm_model->get_company_items($result[0]->crm_c_id);

        $data = array(
            'company_name' => $result[0]->company_name,
            'company_uen' => $result[0]->company_uen,
            'c_contact_number' => $result[0]->c_contact_number,
            'c_email' => $result[0]->c_email,
            'c_postal_code' => $result[0]->c_postal_code,
            'address' => $result[0]->address,
            'c_unit_number' => $result[0]->c_unit_number,
            'c_credit_limit' => $result[0]->c_credit_limit,

        );

        if ($data) {
            // if(count($get_all_items) > 0){
            // for($i=0;$i<count($get_all_items);$i++){
            // 	$data= array(
            // 	'p_ic' => $get_all_items[$i]->p_ic,
            // 	'c_n'=> $get_all_items[$i]->c_n,
            // 	'e_mail'=> $get_all_items[$i]->e_mail,
            // 	'a_dd' => $get_all_items[$i]->a_dd,					
            // 	);
            $session = $this->session->userdata('username');
            $get_all_items = $this->crm_model->get_company_items($id);
            if ($get_all_items) {
                if (count($get_all_items) > 0) {
                    for ($i = 0; $i < count($get_all_items); $i++) {
                        $data = array(
                            'p_ic' => $get_all_items[$i]->p_ic,
                            'c_n' => $get_all_items[$i]->c_n,
                            'e_mail' => $get_all_items[$i]->e_mail,
                            'a_dd' => $get_all_items[$i]->a_dd,
                        );
                        $session = $this->session->userdata('username');
                        if (!empty($session)) {
                            $this->load->view("admin/crm/dialog_companycrm", $data);
                        } else {
                            redirect('admin/');
                        }
                        // Datatables Variables
                        $draw = intval($this->input->get("draw"));
                        $start = intval($this->input->get("start"));
                        $length = intval($this->input->get("length"));
                    }

                    echo json_encode($data);
                    exit();
                }
            }
        }
    }
}
