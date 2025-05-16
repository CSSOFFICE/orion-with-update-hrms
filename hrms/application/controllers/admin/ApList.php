<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ApList extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load the model
        $this->load->model("Supplier_model");
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
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = 'Debtor/Creditor | ' . $this->Xin_model->site_title();
        $data['breadcrumbs'] = 'Debtor/Creditor';
        $data['path_url'] = 'ap_list';
        $data['subview'] = $this->load->view('admin/aplist/ap_list', $data, TRUE);
        $this->load->view('admin/layout/pms/layout_pms', $data); //page load
    }

    public function ap_list()
    {
        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');

        if (empty($session)) {
            redirect('admin/');
        }

        // DataTables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = [];

        // Get merged supplier/client data
        $records = $this->Supplier_model->customer_supplier_data();
        // print_r($records); exit; // Debugging line to check the data structure
        foreach ($records as $r) {
            $data[] = array(
                $r->code,
                $r->name,
                ($r->type === 'supplier') ? 'Vendor' : 'Customer',
                'Both',
                '',
                $r->phone,
                '$0.00',
                '$0.00',
                ($r->type === 'supplier') 
                    ? (($r->expense_gtotal) ? "S$ " . $r->expense_gtotal : 'S$0.00') 
                    : 'S$0.00',
                'FALSE',
            );
        }


        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($records),
            "recordsFiltered" => count($records),
            "data" => $data
        );

        echo json_encode($output);
        exit();
    }
}
