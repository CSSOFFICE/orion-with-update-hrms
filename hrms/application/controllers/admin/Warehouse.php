<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        //load the model
        $this->load->model("product_model");
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

    public function index()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $system = $this->Xin_model->read_setting_info(1);
        if ($system[0]->module_awards != 'true') {
            redirect('admin/dashboard');
        }
        $data['title'] = $this->lang->line('xin_warehouse') . ' | ' . $this->Xin_model->site_title();
        $data['breadcrumbs'] = $this->lang->line('xin_warehouse');
        $data['path_url'] = 'warehouse';
        $data['get_warehouse'] = $this->product_model->get_warehouse();
        $data['get_org'] = $this->product_model->get_company();

        $role_resources_ids = $this->Xin_model->user_role_resource();
        if (in_array('14', $role_resources_ids)) {
            if (!empty($session)) {

                $data['subview'] = $this->load->view("admin/product/warehouse_list", $data, TRUE);
                $this->load->view('admin/layout/pms/layout_pms', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    ////////Functions for warehouse module/////

    public function warehouselist()
    {

        $data['title'] = $this->Xin_model->site_title();
        $session = $this->session->userdata('username');
        $data['get_warehouse'] = $this->product_model->get_warehouse();

        if (!empty($session)) {
            $this->load->view("admin/product/warehouse_list", $data, TRUE);
        } else {
            redirect('admin/');
        }


        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        //get_company_awards
        $role_resources_ids = $this->Xin_model->user_role_resource();

        $get_warehouse = $this->product_model->get_warehouse();
        $data = array();

        foreach ($get_warehouse->result() as $r) {
            //edit
            $edit = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_edit') . '"><button type="button" class="btn icon-btn btn-xs btn-default waves-effect waves-light"  data-toggle="modal" data-target=".edit-modal-data"  data-w_id="' . $r->w_id . '"><span class="fa fa-pencil"></span></button></span>';
            $delete = '<span data-toggle="tooltip" data-placement="top" title="' . $this->lang->line('xin_delete') . '"><button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light delete" data-toggle="modal" data-target=".delete-modal" data-record-id="' . $r->w_id . '"><span class="fa fa-trash"></span></button></span>';
            $view = '<span data-toggle="tooltip" data-placement="top" title="View"><a href="' . site_url() . 'admin/Warehouse/whousePview/' . $r->w_id  . '" class="btn icon-btn btn-xs btn-primary waves-effect waves-light"><span class="fa fa-eye"></span></a></span>';

            $combhr = $edit . $delete . $view;

            $data[] = array(
                $combhr,
                $r->w_name,
                $r->w_address,
                $r->organization,
                $r->w_postal_code,
                $r->w_unit_no

            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $get_warehouse->num_rows(),
            "recordsFiltered" => $get_warehouse->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    public function add_warehouse()
    {
        if ($this->input->post('add_type') == 'warehouse') {

            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('w_name') == '') {
                $Return['error'] = "Warehouse Name Required";
            } else if ($this->input->post('w_address') == '') {
                $Return['error'] = "Warehouse Address Required";
            } else if ($this->input->post('org_id') == '') {
                $Return['error'] = "Organization is Required";
            } else if ($this->input->post('w_postal_code') == '') {
                $Return['error'] = "Warehouse Postal Code Required";
            } else if ($this->input->post('w_uno') == '') {
                $Return['error'] = "Warehouse Unit No Required";
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }

            $data = array(
                'w_name' => $this->input->post('w_name'),
                'w_address' => $this->input->post('w_address'),
                'w_postal_code' => $this->input->post('w_postal_code'),
                'org_id' =>  $this->input->post('org_id'),
                'w_unit_no' => $this->input->post('w_uno'),
                'created_by' => $_SESSION['username']['user_id'],
                'created_at' => date('Y-m-d h:i:s'),
                'w_type'=>'Company'
            );

            $result = $this->product_model->add_warehouse($data);
            if ($result) {
                $Return['result'] = "Warehouse Added Successful";
            } else {
                $Return['error'] = "Something Went Wrong";
            }
            $this->output($Return);
            exit;
        }
    }

    public function add_stock()
    {
        // if ($this->input->post('edit_type') == 'warehouse') {
        $session = $this->session->userdata('username');
        // $data['title'] = "Warehouse Product List";
        // $data['breadcrumbs'] = "Warehouse Product List";
        $data['path_url'] = 'warehouse';
        $data['all_products'] = $this->db->get('product')->result();
        $data['all_supplier'] = $this->db->get('xin_suppliers')->result();




        if (!empty($session)) {

            // $data['subview'] = $this->load->view("admin/product/add_stock", $data, TRUE);
            $this->load->view('admin/product/add_stock', $data); //page load
        } else {
            redirect('admin/');
        }
        // } 
    }

    public function insertStock()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        if ($this->input->post('u_item') == 'Select Product') {
            $Return['error'] = "Please Select Product";
        } else if ($this->input->post('quantity') == '') {
            $Return['error'] = "Quantity Required";
        }

        if ($Return['error'] != '') {
            $this->output($Return);
            exit;
        }

        $prd_id = $this->input->post('u_item');
        $quantity = $this->input->post('quantity');
        $warehouse_id = $this->input->post('warehouse_id');

        // Fetch the current quantity for this product and warehouse
        $this->db->select('quantity');
        $this->db->from('stock_management');
        $this->db->where('prd_id', $prd_id);
        $this->db->where('warehouse_id', $warehouse_id);
        $current_stock = $this->db->get()->row();

        if ($current_stock) {
            // Add the new quantity to the existing quantity
            $updated_quantity = $current_stock->quantity + $quantity;
            // $updated_quantity =  $quantity;

            // Update the stock quantity
            $this->db->where('prd_id', $prd_id);
            $this->db->where('warehouse_id', $warehouse_id);
            $result=$this->db->update('stock_management', ['quantity' => $updated_quantity]);
        } else {
            // Insert new record if not found
            $stock = [
                'prd_id' => $prd_id,
                'quantity' => $quantity,
                'warehouse_id' => $warehouse_id,
                // 'by_whome' => $_SESSION['username']['user_id'],
                // 'add_date' => date('Y-m-d h:i:s'),
                // 'remark_for_stock_add' => "This Item and Quantity Added Manually"
            ];
            $result=$this->db->insert('stock_management', $stock);
        }
        $project_id=$this->db->select('project_id')->from('projects')->where('warehouse_id', $warehouse_id)->get()->result();
        $stock_move_data = array(
            'product_id' => $prd_id,
            'remark' => "This Item and Quantity Added Manually",
            'qtn' => $quantity,
            // 'prj_id' => $project_id[0]->project_id,
            'wh_id' => $warehouse_id,
            'trans_type' => 'INBOUND',
            'movement_type' => 'Receive',
            'stock_from' =>	 $warehouse_id,
            'stock_to' => $warehouse_id,
            'from_to_type' => "Warehouse Stock Add",
            'created_date' => date('Y-m-d'),
            'by_whome' => $_SESSION['username']['user_id']

        );
        // print_r($stock_move_data);exit;
        $this->db->insert('stock_move_log', $stock_move_data);

        if ($result) {
            $Return['result'] = "Product added to Warehouse";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
    }

    public function read()
    {

        $data['title'] = $this->Xin_model->site_title();
        $id = $this->input->get('w_id');
        $data['get_org'] = $this->product_model->get_company();


        $result = $this->product_model->read_warehouse($id);
        $data = array(
            'org_id' => $result[0]->org_id,
            'w_id' => $result[0]->w_id,
            'w_name' => $result[0]->w_name,
            'w_address' => $result[0]->w_address,
            'w_postal_code' => $result[0]->w_postal_code,
            'w_unit_no' => $result[0]->w_unit_no,
            'get_org' => $this->product_model->get_company()
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view('admin/product/dialog_warehouse', $data);
        } else {
            redirect('admin/');
        }
    }
    public function update()
    {

        if ($this->input->post('edit_type') == 'warehouse') {
            $id = $_POST['w_id'];

            /* Define return | here result is used to return user data and error for error message */
            $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
            $Return['csrf_hash'] = $this->security->get_csrf_hash();

            if ($this->input->post('w_name') == '') {
                $Return['error'] = "Warehouse Name Require";
            } else if ($this->input->post('w_address') == '') {
                $Return['error'] = "Warehouse Address Require";
            } else if ($this->input->post('w_postal_code') == '') {
                $Return['error'] = "Warehouse Postal Code";
            } else if ($this->input->post('w_unit_no') == '') {
                $Return['error'] = "Warehouse Unit No";
            }

            if ($Return['error'] != '') {
                $this->output($Return);
                exit;
            }

            $data = array(
                'w_name' => $this->input->post('w_name'),
                'w_address' => $this->input->post('w_address'),
                'w_postal_code' => $this->input->post('w_postal_code'),
                'org_id' => $this->input->post('org_id'),
                'w_unit_no' => $this->input->post('w_unit_no'),
                'modified_by' => $_SESSION['username']['user_id'],
                'modified_at' => date('Y-m-d h:i:s'),
            );
            $result = $this->product_model->update_warehouse($data, $id);

            if ($result) {


                $Return['result'] = "Warehouse Updated Successfully";
            } else {
                $Return['error'] = "Something Went Wrong";
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

        $result = $this->product_model->delete_warehouse($id);
        if ($result) {
            $Return['result'] = "Warehouse Deleted Successfully";
        } else {
            $Return['error'] = "Something Went Wrong";
        }
        $this->output($Return);
        // exit;
    }

    public function get_product_detail()
    {

        $data['title'] = $this->Xin_model->site_title();
        $id = $this->uri->segment(4);

        $result = $this->product_model->read_warehouse($id);
        //echo "<pre>";print_r($result);exit;
        echo json_encode($result);
    }


    public function whousePview($whouse_id)
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $data['title'] = "Warehouse Product List";
        $data['breadcrumbs'] = "Warehouse Product List";
        $data['path_url'] = 'warehouse';



        if (!empty($session)) {

            $data['subview'] = $this->load->view("admin/product/wpreview2", $data, TRUE);
            $this->load->view('admin/layout/pms/layout_pms', $data); //page load
        } else {
            redirect('admin/');
        }

      
    }

    public function stocktake($whouse_id)
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $data['title'] = "Stock Take Report";
        $data['breadcrumbs'] = "Stock Take Report";
        $data['path_url'] = 'warehouse';



        if (!empty($session)) {

            $data['subview'] = $this->load->view("admin/product/wpreview", $data, TRUE);
            $this->load->view('admin/layout/pms/layout_pms', $data); //page load
        } else {
            redirect('admin/');
        }
    }
    function stocktransfer()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }

        $data['title'] = "Stock Transfer";
        $data['breadcrumbs'] = "Stock Transfer";
        $data['path_url'] = 'warehouse';



        if (!empty($session)) {
            $data['subview'] = $this->load->view("admin/product/stocktransfer", $data, TRUE);
            $this->load->view('admin/layout/pms/layout_pms', $data); //page load
        } else {
            redirect('admin/');
        }
    }
    // Controller: Warehouse.php
    public function transfer_stock($from_warehouse_id)
    {
        $transferData = $this->input->post('transferData');
        // print_r($transferData);exit;
        $response = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $response['csrf_hash'] = $this->security->get_csrf_hash();
        if (!empty($transferData)) {
            foreach ($transferData as $data) {
                $product_name = $data['product_name'];
                $product_id = $data['product_id'];
                $transfer_qty = $data['quantity'];
                $to_warehouse_id = $data['warehouse_id'];
                // $from_warehouse_id = $this->uri->segment(4); // Current warehouse ID from URI

                // Validate that the transfer quantity does not exceed current stock
                $current_stock = $this->product_model->get_stock($from_warehouse_id, $product_id);
                // print_r($current_stock);exit;
                if ($transfer_qty > $current_stock) {
                    $response['error'] = "Error: Transfer quantity for $product_name exceeds available stock.";
                    echo json_encode($response);
                    return;
                }

                // Perform stock transfer
                $this->product_model->transfer_stock($from_warehouse_id, $to_warehouse_id, $product_id, $transfer_qty);
                $stock_move_data = array(
                    'product_id' => $product_id,
                    'remark' => "Stock Transfer from warehouse to Warehouse",
                    'qtn' => $transfer_qty,
                    'prj_id' => 0,
                    'wh_id' => $from_warehouse_id,
                    'trans_type' => 'INBOUND',
                    'stock_from' =>	 $from_warehouse_id,
                    'stock_to' => $to_warehouse_id,
                    'from_to_type' => "warehouse to warehouse",
                    'created_date' => date('Y-m-d'),
                    'by_whome'=>$_SESSION['username']['user_id']
                );
                $this->db->insert('stock_move_log', $stock_move_data);
            }
            // $response['status'] = true;
            $response['result'] = 'Stock transferred successfully!';
        } else {
            $response['error'] = 'No valid data received for stock transfer.';
        }

        echo json_encode($response);
        exit();
    }


    function list($whouse_id)
    {
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        // Select data with conditional aggregation and logic for qty_rem
        $list = $this->db->select('stock_management.warehouse_id,
                                   product.product_name,                                   
                                   stock_management.quantity')
            ->from('stock_management')
            ->join('product', 'stock_management.prd_id = product.product_id', 'left')
            ->where('stock_management.warehouse_id', $whouse_id)
            // ->group_by('grn_log.item')
            ->get();
        $data = array();
        $i = 0;
        foreach ($list->result() as $r) {
            $i++;
            // Calculate qty_rem based on wh_no value
            // $adjusted_qty_rem = ($r->wh_no == 'p') ? $r->qty_rem - $r->qtn : $r->qty_rem;
            // Prepare the data array
            $data[] = array(
                $i,
                $r->product_name,
                $r->quantity,
                // $adjusted_qty_rem,
            );
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $list->num_rows(),
            "recordsFiltered" => $list->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    function update_quantity()
    {
        // print_r($this->input->post('current_qty'));exit;
        // return "hello";
        $current_qty = $this->input->post('current_qty');
        $p_stock_id = $this->input->post('p_id');
        $warehouse_id = $this->input->post('warehouse_id');

        $Return = array('result' => '', 'error' => '', 'csrf_hash' => '');
        $Return['csrf_hash'] = $this->security->get_csrf_hash();

        $result = '';
        for ($i = 0; $i < count($p_stock_id); $i++) {
            if (!empty($current_qty[$i])) {
                // Update the stock quantity
                $this->db->where('prd_id', $p_stock_id[$i]);
                $this->db->where('warehouse_id', $warehouse_id);
                $result = $this->db->update('stock_management', ['quantity' => $current_qty[$i]]);
            }
        }

        if ($result) {
            $Return['result'] = "Quantity Updated";
        } else {
            $Return['error'] = "Something Went Wrong";
        }

        echo json_encode($Return);
        exit();
    }




    ////////END Inventory Module/////////
}
